H default ����������� ��� Drupal 7 ���� �������������� �� Image module (Provides image manipulation tools). �� ���� ��� ����� ������� � ���������� �� ����������� ��� Image �� �� ���������� ���� ���� Article. 
��� path Configuration->Media->Image Styles (Configure styles that can be used for resizing or adjusting images on display), ������� � ���������� ��� �����������/������������ ��� Image Styles. �������� 3 ������ Image Styles (thumbnail, medium, preview). To thumbnail ��������������� ���� ��� ��������� ��� Image, �� preview ��� default front page ��� ��� taxonomy pages ��� �� large ���� ��� ������� ��� ������.

��� �� ��������� ��� �������� ���� ���� �������� ��� Journal Crunch 7 ������� �� ����, 

1. ����������� ��� Preview Image Style Effect �� Scale and crop 430x290 
- �� ���� ��� ������ ������������� ��� ��� �� images ��� articles �� front page ��� taxonomy pages ��� �� ��������� ����� ��� ����������. ������ ���� � �������� ����� �o attached image ��� sticky articles. 
��� �o attached image ��� non-sticky articles �� front page ��� taxonomy pages, ��������� �� ��� ������ ���������� (CSS based*) ��� ���� image �� Preview Image Style ��� ���� ������ ��� ���������� canva.

*
Line 318-Image div placeholder
.node-front div.field-type-image { display: block; height: 120px; overflow: hidden; }
Line 311-Image width 
#content .node-front .nodeInner img { overflow: hidden; padding: 0; width: 255px; } 

2. ����������� ��� Large Image Style Effect �� Scale and crop 593x348
- ��������� �������� ��� attached image �� node page.