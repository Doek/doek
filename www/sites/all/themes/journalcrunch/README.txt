H default εγκατάσταση του Drupal 7 έχει ενεργοποιημένο το Image module (Provides image manipulation tools). Με αυτό τον τρόπο υπάρχει η δυνατότητα να επισυναφθεί ένα Image με τη δημιουργία ενός νέου Article. 
Στο path Configuration->Media->Image Styles (Configure styles that can be used for resizing or adjusting images on display), υπάρχει η δυνατότητα της δημιουργίας/επεξεργασίας των Image Styles. Υπάρχουν 3 έτοιμα Image Styles (thumbnail, medium, preview). To thumbnail χρησιμοποιείται κατά την επισύναψη του Image, το preview στη default front page και στα taxonomy pages και το large κατά την προβολή του άρθρου.

Για να πετύχουμε την εμφάνιση όπως αυτή φαίνεται στο Journal Crunch 7 κάνουμε τα εξής, 

1. Τροποποίηση του Preview Image Style Effect σε Scale and crop 430x290 
- Με αυτή την αλλαγή εξασφαλίζουμε ότι όλα τα images των articles σε front page και taxonomy pages δεν θα ξεπερνούν αυτές τις διαστάσεις. Βέβαια αυτή η διάσταση αφορά τo attached image των sticky articles. 
Για τo attached image των non-sticky articles σε front page και taxonomy pages, προχωράμε σε μια αλλαγή διαστάσεων (CSS based*) στο ίδιο image με Preview Image Style και στον ορισμό του επιθυμητού canva.

*
Line 318-Image div placeholder
.node-front div.field-type-image { display: block; height: 120px; overflow: hidden; }
Line 311-Image width 
#content .node-front .nodeInner img { overflow: hidden; padding: 0; width: 255px; } 

2. Τροποποίηση του Large Image Style Effect σε Scale and crop 593x348
- Επιθυμητή διάσταση του attached image σε node page.