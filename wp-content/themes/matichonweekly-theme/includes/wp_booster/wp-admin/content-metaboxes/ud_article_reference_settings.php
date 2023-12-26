<span class="td-full-width-label">
    Author Info
    <?php
    td_util::tooltip_html('
                <h3>Author Name and URL: </h3>
                <p>You can force author\'s name and url of this article on input boxes below respectively.</p>
            ', 'right')
    ?>
</span>
<p>
    <?php $mb->the_field('author_name'); ?>
    <label>name:</label>
    <input style="width: 100%;" type="text" name="<?php $mb->the_name(); ?>" value="<?php $mb->the_value(); ?>"/>
    <?php $mb->the_field('author_url'); ?>
    <label>url:</label>
    <input style="width: 100%;" type="text" name="<?php $mb->the_name(); ?>" value="<?php $mb->the_value(); ?>"/>
</p>

<?php while($mb->have_fields_and_multi('ud_article_refs', array('length' => 1, 'limit' => 5))): ?>

    <?php $mb->the_group_open(); ?>

    <span class="td-full-width-label">
        Reference
        <?php
        td_util::tooltip_html('
                <h3>Ref. Name and URL:</h3>
                <p>You can set Reference\'s name and url of this article on two input boxes below respectively.</p>
            ', 'right')
        ?>
    </span>

    <p>
        <?php $mb->the_field('name'); ?>
        <label>name:</label>
        <input style="width: 100%;" type="text" name="<?php $mb->the_name(); ?>" value="<?php $mb->the_value(); ?>"/>
        <?php $mb->the_field('url'); ?>
        <label>url:</label>
        <input style="width: 100%;" type="text" name="<?php $mb->the_name(); ?>" value="<?php $mb->the_value(); ?>"/>
    </p>

    <p><a href="#" class="dodelete button">Delete</a></p>

    <?php $mb->the_group_close(); ?>
<?php endwhile; ?>

<p><a href="#" class="docopy-ud_article_refs button">Add More Reference</a></p>
