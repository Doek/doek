#!/usr/bin/env perl 
use strict;
use warnings;
use Carp;

use DBI;
use FindBin qw($Bin);
use Cwd qw(abs_path);
use Data::Dumper;

my $dryrun = 0;

my $BASEDIR = abs_path("$Bin/..");

# Read Config file
my %cfg_dist = grep { defined $_ } map { /^([A-Z_]+\s*)=(\s*.*)/ } 
    read_file("$BASEDIR/devel/config.dist.sh");

my %cfg_local = grep { defined $_ } map { /^([A-Z_]+\s*)=(\s*.*)/ } 
    read_file("$BASEDIR/devel/config.sh");

my %cfg = (%cfg_dist, %cfg_local);

my $dbh = DBI->connect("DBI:mysql:database=information_schema",
    $cfg{LOCAL_DBUSER}, $cfg{LOCAL_DBPASS}, {
        PrintError => 0,
        PrintWarn => 1,
        RaiseError => 1,
} ) or die "Could not connect to MySQL";
$dbh->do('SET character_set_results="utf8"');
$dbh->do('SET collation_connection = @@collation_database;');

# Find out if the database exists
my $tables = $dbh->selectall_arrayref(
    "select schema_name from SCHEMATA where SCHEMA_NAME = ?", { Slice => {} }, $cfg{LOCAL_DBNAME}
);

# Create password string for commandline
my $cmd_password = $cfg{LOCAL_DBPASS} !~ /^\s*$/ ? "-p$cfg{LOCAL_DBPASS}" : '';

# Backup up the database and drop if it exists
if(@{$tables} > 0 and !$dryrun) {
    system("mysqldump -u $cfg{LOCAL_DBUSER} $cmd_password $cfg{LOCAL_DBNAME} > $BASEDIR/backup.sql");
    $dbh->do("DROP DATABASE $cfg{LOCAL_DBNAME}");
}

if(!$dryrun) {
    # Create database and load tables and data from production
    $dbh->do("CREATE DATABASE $cfg{LOCAL_DBNAME}");
    system("ssh -C $cfg{SSHUSER}\@$cfg{HOST} mysqldump -u$cfg{DBUSER} -p$cfg{DBPASS} $cfg{DBNAME}"
            ."|mysql -u $cfg{LOCAL_DBUSER} $cmd_password $cfg{LOCAL_DBNAME}");
    die "Data import from production failed : $? " if $? != 0;
}

$dbh->do("USE $cfg{LOCAL_DBNAME}");

if(!$dryrun) {
    system "sudo chown -R $ENV{USER}:$ENV{USER} $BASEDIR/sites/default/files/";
    system "rsync -a --exclude '.gitignore' --exclude 'default.settings.php' "
    ."$cfg{SSHUSER}\@$cfg{HOST}:$cfg{WEBPATH}/sites/default/ "
    ."$BASEDIR/sites/default/";

    system "sudo chown -R $ENV{USER}:$ENV{USER} $BASEDIR/sites/default/files/";
    system "chmod ogu+rw $BASEDIR/sites/default/ -R";
}

# Fix config files
my @files = qw{
    sites/default/settings.php
};

foreach my $conf (@files) {
    next if !-f "$BASEDIR/$conf"; # skip if the conf file does not exists
    my $data = read_file("$BASEDIR/$conf");
    $data =~ s/$cfg{WEBPATH}/$BASEDIR/sg; # replace path information
    $data =~ s/('username'\s*=>\s*').+?('\s*,)/$1$cfg{LOCAL_DBUSER}$2/sg;
    $data =~ s/('password'\s*=>\s*').+?('\s*,)/$1$cfg{LOCAL_DBPASS}$2/sg;
    write_file("$BASEDIR/$conf", $data);
}

sub read_file {
    open(my $fh, $_[0]) or die "Could not open file $_[0] : $!\n";
    my $data = do { local( $/ ) ; <$fh> };
    close $fh;
    if(wantarray) {
        return split '\n', $data;
    } else {
        return $data;
    }
}

sub write_file {
    open(my $fh, ">", $_[0]) or die "Could not open file $_[0] : $!\n";
    print {$fh} $_[1]; 
    close $fh;
}
