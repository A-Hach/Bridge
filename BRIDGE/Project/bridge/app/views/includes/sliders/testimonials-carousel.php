<?php if (is_null($data['testimonials']) || count($data['testimonials']) == 0) : ?>
    <div class="regular mr-b-20">Il n'y a pas de témoignage, soyez le premier!</div>
<?php else : ?>
    <div id="testimonialsCarousel" class="carousel slide section-container" data-interval="3000" data-ride="carousel">
        <div class="carousel-inner">
            <?php $first = 1; ?>
            <?php foreach ($data['testimonials'] as $testimonial) : ?>
                <div class="carousel-item<?php if ($first == 1) {
                                                echo ' active';
                                                $first = 0;
                                            }; ?>">
                    <div>
                        <img class="user-img br" src="<?php getFileURL($testimonial['main_picture']); ?>" alt="Image">
                        <h3>
                            <img src="<?php getFileURL($testimonial['badge']); ?>" alt="Image" role="icon">
                            <?php echo $testimonial['display_name']; ?>
                        </h3>
                        <div class="info">
                            <?php echo $testimonial['name'] . ', ' . getElapsedTime($testimonial['date']); ?>
                        </div>
                        <div class="text">
                            <?php echo $testimonial['text']; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <a class="carousel-control-prev purple" href="#testimonialsCarousel" role="button" data-slide="prev">
            <span><i class="fas fa-chevron-left fa-fw"></i></span>
            <span class="sr-only">Précédent</span>
        </a>
        <a class="carousel-control-next purple" href="#testimonialsCarousel" role="button" data-slide="next">
            <span><i class="fas fa-chevron-right fa-fw"></i></span>
            <span class="sr-only">Suivant</span>
        </a>
    </div>
    <script>
        $(document).ready(function() {
            // Fix carousel width
            let carouselItems = $('#testimonialsCarousel .carousel-item');

            let maxHeight = carouselItems.eq(0).height();

            for (let i = 1; i < carouselItems.length; i++)
                maxHeight = carouselItems.eq(i).height() > maxHeight ? carouselItems.eq(i).height() : maxHeight;

            for (let i = 0; i < carouselItems.length; i++)
                carouselItems.eq(i).height(maxHeight + "px");
        });
    </script>
<?php endif; ?>