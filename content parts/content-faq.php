<article <?php tc__f( '__article_selectors' ) ?>>

    <?php do_action( '__before_content' ); ?>

        <section class="tc-content <?php echo $_layout_class; ?>">

            <div class="entry-content">

                <div class="clear">
                    <?php the_content(); ?>
                </div>

            </div>

        </section>

    <?php do_action( '__after_content' ); ?>

</article>