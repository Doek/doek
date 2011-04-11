// $Id: rpx_widgets.js,v 1.6 2011/01/10 16:27:10 geokat Exp $
(function ($) {

Drupal.behaviors.rpx = {
  attach: function (context, settings) {
    function popupSocial(post) {
      RPXNOW.loadAndRun(['Social'], function () {
        var activity = new RPXNOW.Social.Activity(
          post.label,
          post.linktext,
          post.link
        );
        activity.setUserGeneratedContent(post.comment);
        activity.setDescription(post.summary);
        RPXNOW.Social.publishActivity(activity);
      });
    };
    if ('rpx' in settings && 'atonce' in settings.rpx) {
      popupSocial(settings.rpx['atonce']);
    }
    $('.rpx-link-social', context).once('rpx-link-social', function() {
      var post = settings.rpx[$(this).attr('id')];
      $(this).bind('click', function(e) {popupSocial(post); return false;});
    });
  }
};

})(jQuery);
