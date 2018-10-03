<?php
function create_post_type_web() {

    register_taxonomy('site', 'web', array(
        'label' => 'Site',
        'singular_label' => 'Site',
        'hierarchical' => true,
        'query_var' => true,
        'rewrite' => array(
            'slug' => 'site'
        )
    ));
    
    $labels = array(
        'name' => __('Web', 'theme'),
        'singular_name' => __('Web', 'theme'),
        'rewrite' => array(
            'slug' => __('web', 'theme')
        ),
        'add_new' => _x('Adicionar item', 'web', 'theme'),
        'add_new_item' => __('Adicionar Novo item', 'theme'),
        'edit_item' => __('Editar item', 'theme'),
        'new_item' => __('Novo item', 'theme'),
        'view_item' => __('Ver item', 'theme'),
        'search_items' => __('Pesquisar', 'theme'),
        'not_found' => __('Nenhuma item encontrado', 'theme'),
        'not_found_in_trash' => __('Nenhum item encontrado na lixeira', 'theme'),
        'parent_item_colon' => ''
    );
    
    $args = array(
        'labels' => $labels,
        'public' => true,
        'exclude_from_search' => false,
        'publicly_queryable' => true,
        'show_ui' => true,
        'query_var' => true,
        'rewrite' => array(
            'slug' => 'web',
            'with_front' => true
        ),
        'capability_type' => 'post',
        'supports' => array(
            'title',
            'editor',
            'thumbnail'
        ),
        'taxonomies' => array(
            'categoria'
        ),
        'menu_position' => 5,
        'has_archive' => 'portfolio/web'
    );
    
    register_post_type('web', $args);
    flush_rewrite_rules();
    
}

add_action('init', 'create_post_type_web');


function my_taxonomy_add_meta_fields( $taxonomy ) {
    ?>
    <div class="form-field term-group">
        <label for="site_url">Url:<input type="text" id="site_url" name="site_url" /></label>
        <label for="site_ecommerce"><input  type="checkbox" id="site_ecommerce" name="site_ecommerce" />Ecommerce</label>
    </div>
    <?php
}
add_action( 'site_add_form_fields', 'my_taxonomy_add_meta_fields', 10, 2 );

function my_taxonomy_edit_meta_fields( $term, $taxonomy ) {
    $site_url = get_term_meta( $term->term_id, 'site_url', true );
    $site_ecommerce = get_term_meta( $term->term_id, 'site_ecommerce', true );
    ?>
    <tr class="form-field term-group-wrap">
        <th scope="row">
            <label for="site_url">Url:</label>
        </th>
        <td>
            <input type="text" id="site_url" name="site_url" value="<?php echo $site_url; ?>" />
        </td>
    </tr>
    <tr class="form-field term-group-wrap">
        <th scope="row">
            <label for="site_ecommerce">Ecommerce</label>
        </th>
        <td>
            <input  type="checkbox" id="site_ecommerce" name="site_ecommerce" <?php  echo ($site_ecommerce? 'checked="checked"':'') ?>/>
        </td>
    </tr>
    <?php
}
add_action( 'site_edit_form_fields', 'my_taxonomy_edit_meta_fields', 10, 2 );

function my_taxonomy_save_taxonomy_meta( $term_id, $tag_id ) {
    if( isset( $_POST['site_url'] ) ) {
        update_term_meta( $term_id, 'site_url', esc_attr( $_POST['site_url'] ) );
    }
    if( isset( $_POST['site_ecommerce'] ) ) {
        update_term_meta( $term_id, 'site_ecommerce', esc_attr( $_POST['site_ecommerce'] ) );
    }
}
add_action( 'created_site', 'my_taxonomy_save_taxonomy_meta', 10, 2 );
add_action( 'edited_site', 'my_taxonomy_save_taxonomy_meta', 10, 2 );
