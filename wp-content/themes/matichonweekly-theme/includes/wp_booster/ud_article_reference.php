<?php

class ud_article_reference {
    static function on_save_post_article_reference( $meta, $post_id ) {
        if ( wp_is_post_revision( $post_id ) ) {
            return $meta;
        }

        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return $meta;
        }

        if ( isset( $meta['author_name'] ) ) {
            $meta['author_name'] = sanitize_text_field( $meta['author_name'] );
        }

        if ( isset( $meta['author_url'] ) ) {
            $meta['author_url'] = sanitize_text_field( $meta['author_url'] );
        }

        if ( isset( $meta['ud_article_refs'] ) ) {
            foreach ( $meta['ud_article_refs'] as $ref ) {
                if ( isset( $ref['name'] ) ) {
                    $ref['name'] = sanitize_text_field( $ref['name'] );
                }

                if ( isset( $ref['url'] ) ) {
                    $ref['url'] = sanitize_text_field( $ref['url'] );
                }
            }
        }

        return $meta;
    }
}
