<?php
/*-----------------------------------------------------------------------------------*/
/*  Post Type Cardápio
/*-----------------------------------------------------------------------------------*/

function create_post_type_cardapio() {

    register_taxonomy('unidade', 'cardapio', array(
        'label' => 'Unidade',
        'singular_label' => 'Unidade',
        'hierarchical' => true,
        'query_var' => true,
        'rewrite' => array(
            'slug' => 'unidade'
        )
    ));

    register_taxonomy('categoria', 'cardapio', array(
        'label' => 'Categoria',
        'singular_label' => 'Categoria',
        'hierarchical' => true,
        'query_var' => true,
        'rewrite' => array(
            'slug' => 'categoria'
        )
    ));
    
    $labels = array(
        'name' => __('Cardápio', 'theme'),
        'singular_name' => __('Cardápio', 'theme'),
        'rewrite' => array(
            'slug' => __('cardapio', 'theme')
        ),
        'add_new' => _x('Adicionar item', 'cardapio', 'theme'),
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
            'slug' => 'cardapio/%unidade%/%categoria%',
            'with_front' => true
        ),
        'capability_type' => 'post',
        'supports' => array(
            'title',
            'editor'
        ),
        'taxonomies' => array(
            'categoria', 'unidade'
        ),
        'menu_position' => 5,
        'has_archive' => true
    );
    
    register_post_type('cardapio', $args);
    flush_rewrite_rules();
    
}

add_action('init', 'create_post_type_cardapio');


function cardapio_permalink_structure($post_link, $post, $leavename, $sample) {
    
    if (false !== strpos($post_link, '%categoria%')) {
        $terms = get_the_terms($post->ID, 'categoria');
        if (is_array($terms)) {
            $post_link = str_replace('%categoria%', array_pop($terms)->slug, $post_link);
        } else {
            $post_link = str_replace('%categoria%', "", $post_link);
        }
    }
     if (false !== strpos($post_link, '%unidade%')) {
        $terms = get_the_terms($post->ID, 'unidade');
        if (is_array($terms)) {
            $post_link = str_replace('%unidade%', array_pop($terms)->slug, $post_link);
        } else {
            $post_link = str_replace('%unidade%', "", $post_link);
        }
    }
    
    return $post_link;
}

add_filter('post_type_link', 'cardapio_permalink_structure', 10, 4);

function my_edit_categoria_columns($columns) {
    
    $columns = array(
        'cb' => '<input type="checkbox" />',
        'title' => __('Conteúdo'),
        'unidade' => __('Unidade'),
        'categoria' => __('Categoria'),
        'preco' => __('Preço')
    );
    
    return $columns;
}

add_filter('manage_edit-cardapio_columns', 'my_edit_categoria_columns');


function manage_cardapio_columns($column, $post_id) {
    switch ($column) {
        
        case 'unidade':
            $terms = get_the_terms(get_the_ID(), 'unidade');
            if ($terms && !is_wp_error($terms)) {
                
                $categories = array();
                
                foreach ($terms as $term) {
                    $categories[] = $term->name;
                }
                
                $on_draught = join(", ", $categories);
                printf(esc_html__('%s.', 'textdomain'), esc_html($on_draught));
                
            }
        break;
        
        case 'categoria':
            $terms = get_the_terms(get_the_ID(), 'categoria');
            if ($terms && !is_wp_error($terms)) {
                
                $categories = array();
                
                foreach ($terms as $term) {
                    $categories[] = $term->name;
                }
                
                $on_draught = join(", ", $categories);
                printf(esc_html__('%s.', 'textdomain'), esc_html($on_draught));
                
            }
        break;
        
        case 'preco':
            $price = get_post_meta( get_the_ID(), 'car_preco', true );
            echo $price;
        break;

        default:
            break;
    }
    
}

add_action('manage_cardapio_posts_custom_column', 'manage_cardapio_columns', 10, 2);

function cardapio_sort($columns) {
    $custom = array(
        'unidade' => 'unidade',
        'categoria' => 'categoria'
    );
    return wp_parse_args($custom, $columns);
}

add_filter("manage_edit-cardapio_sortable_columns", 'cardapio_sort');




    
function add_cardapio_metaboxes() {
    add_meta_box('infos-cardapio', 'Infos', 'createFieldsCardapio', 'cardapio', 'normal', 'default');
}

add_action( 'add_meta_boxes', 'add_cardapio_metaboxes' );

function createFieldsCardapio(){
    global $post;
       
    $car_pizza = get_post_meta($post->ID, 'car_pizza', true);
    $car_tipo_pizza = get_post_meta($post->ID, 'car_tipo_pizza', true);
    $car_produto_com_preco = get_post_meta($post->ID, 'car_produto_com_preco', true);    
    $car_cerveja = get_post_meta($post->ID, 'car_cerveja', true);
    $car_preco = get_post_meta($post->ID, 'car_preco', true);
    $car_quantidade = get_post_meta($post->ID, 'car_quantidade', true);
    $car_numero = get_post_meta($post->ID, 'car_numero', true);
    echo '<div class="box-input-cardapio">';
    echo '<p>Tipo do item</p>';
    echo '<label style="display: block;float: left;width: 40px;line-height: 27px;" for="car_pizza">Pizza:</label><input style="margin-top: 6px;" type="checkbox" id="car_pizza" name="car_pizza" ' .($car_pizza? 'checked="checked"':''). '/>';
    echo '<div style="clear:both"></div>';
    echo '<label style="display: block;float: left;width: 85px;line-height: 27px;" for="car_tipo_pizza">Tipo de pizza:</label><input style="margin-top: 6px;" type="checkbox" id="car_tipo_pizza" name="car_tipo_pizza" ' .($car_tipo_pizza? 'checked="checked"':''). '/>';
    echo '<div style="clear:both"></div>';
    echo '<label style="display: block;float: left;width: 120px;line-height: 27px;" for="car_produto_com_preco">Produto com preço:</label><input style="margin-top: 6px;" type="checkbox" id="car_produto_com_preco" name="car_produto_com_preco" ' .($car_produto_com_preco? 'checked="checked"':''). '/>';
    echo '<div style="clear:both"></div>';    
    echo '<label style="display: block;float: left;width: 50px;line-height: 27px;" for="car_cerveja">Cerveja:</label><input style="margin-top: 6px;" type="checkbox" id="car_cerveja" name="car_cerveja" ' .($car_cerveja? 'checked="checked"':''). '/>';
    echo '<div style="clear:both"></div>';
    echo '<p>Descrição</p>'; 
    echo '<label style="display: block;float: left;width: 75px;line-height: 27px;" for="car_preco">Número:</label><input style="width:100px;margin-bottom:10px;" type="text" id="car_numero" name="car_numero" value="' . $car_numero  . '"/>';
    echo '<div style="clear:both"></div>';
    echo '<label style="display: block;float: left;width: 75px;line-height: 27px;" for="car_preco">Preço:</label><input style="width:100px;margin-bottom:10px;" type="text" id="car_preco" name="car_preco" value="' . $car_preco  . '"/>';
    echo '<div style="clear:both"></div>';   
    echo '<label style="display: block;float: left;width: 75px;line-height: 27px;" for="car_quantidade">Quantidade:</label><input style="width:100px;margin-bottom:10px;" type="text" id="car_quantidade" name="car_quantidade" value="' . $car_quantidade  . '"/>';
    echo '<div style="clear:both"></div>';
    
    echo '<div style="clear:both"></div>';
    echo '<input type="hidden" name="cardapio_noncename" id="cardapio_noncename" value="' . wp_create_nonce( plugin_basename(__FILE__) ) . '" />';
    echo '</div>';
}

function save_cardapio_meta($post_id, $post) {       
    if(!empty($_POST)){
        if(isset($_POST['cardapio_noncename'])){
            if ( !wp_verify_nonce( $_POST['cardapio_noncename'], plugin_basename(__FILE__) )) {
                return $post->ID;
            }
            if ( !current_user_can( 'edit_post', $post->ID ))
                return $post->ID;
            $events_meta['car_pizza'] = isset($_POST['car_pizza'])?1:0;
            $events_meta['car_tipo_pizza'] = isset($_POST['car_tipo_pizza'])?1:0;

            $events_meta['car_preco'] = $_POST['car_preco'];
            $events_meta['car_quantidade'] = $_POST['car_quantidade'];            
            $events_meta['car_produto_com_preco'] = isset($_POST['car_produto_com_preco'])?1:0;
            $events_meta['car_cerveja'] = isset($_POST['car_cerveja'])?1:0;
            $events_meta['car_numero'] = $_POST['car_numero'];

            foreach ($events_meta as $key => $value) { // Cycle through the $events_meta array!
                if( $post->post_type == 'revision' ) return; // Don't store custom data twice
                $value = implode(',', (array)$value); // If $value is an array, make it a CSV (unlikely)
                if(get_post_meta($post->ID, $key, FALSE)) { // If the custom field already has a value
                    update_post_meta($post->ID, $key, $value);
                } else { // If the custom field doesn't have a value
                    add_post_meta($post->ID, $key, $value);
                }
                if(!$value) delete_post_meta($post->ID, $key); // Delete if blank
            }
        }
        
    }
}

add_action('save_post', 'save_cardapio_meta', 1, 2); 


add_action( 'bulk_edit_custom_box', 'cardapio_add_to_bulk_quick_edit_custom_box', 10, 2 );
add_action( 'quick_edit_custom_box', 'cardapio_add_to_bulk_quick_edit_custom_box', 10, 2 );
function cardapio_add_to_bulk_quick_edit_custom_box( $column_name, $post_type ) {
   switch ( $post_type ) {
      case 'cardapio':

         switch( $column_name ) {
            case 'preco':
               ?><fieldset class="inline-edit-col-right">
                  <div class="inline-edit-group">
                     <label>
                        <span class="title">Preço:</span>
                        <input type="text" name="preco" value="" />
                     </label>
                  </div>
               </fieldset><?php
               break;
         }
         break;

   }
}

add_action( 'admin_print_scripts-edit.php', 'cardapio_enqueue_edit_scripts' );
function cardapio_enqueue_edit_scripts() {
   wp_enqueue_script( 'cardapio-admin-edit', get_bloginfo( 'stylesheet_directory' ) . '/assets/js/quick_edit.js', array( 'jquery', 'inline-edit-post' ), '', true );
}

add_action( 'save_post','cardapio_save_post', 10, 2 );
function cardapio_save_post( $post_id, $post ) {

   // don't save for autosave
   if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
      return $post_id;

   // dont save for revisions
   if ( isset( $post->post_type ) && $post->post_type == 'revision' )
      return $post_id;

   switch( $post->post_type ) {

      case 'cardapio':

        if ( array_key_exists( 'preco', $_POST ) )
        update_post_meta( $post_id, 'car_preco', $_POST[ 'preco' ] );

     break;

   }

}

add_action( 'wp_ajax_cardapio_save_bulk_edit', 'cardapio_save_bulk_edit' );
function cardapio_save_bulk_edit() {
   // get our variables
   $post_ids = ( isset( $_POST[ 'post_ids' ] ) && !empty( $_POST[ 'post_ids' ] ) ) ? $_POST[ 'post_ids' ] : array();
   $preco = ( isset( $_POST[ 'preco' ] ) && !empty( $_POST[ 'preco' ] ) ) ? $_POST[ 'preco' ] : NULL;
   // if everything is in order
   if ( !empty( $post_ids ) && is_array( $post_ids ) && !empty( $preco ) ) {
      foreach( $post_ids as $post_id ) {
         update_post_meta( $post_id, 'preco', $preco );
      }
   }
}

function my_restrict_manage_posts() {
    global $typenow;
    
    $args = array(
        'public' => true,
        '_builtin' => false
    );
    $post_types = get_post_types($args);
    
    if (in_array($typenow, $post_types)) {
        
        $filters = get_object_taxonomies($typenow);
        
        foreach ($filters as $tax_slug) {

            $tax_obj = get_taxonomy($tax_slug);     
            if($tax_slug == 'categoria'){
                wp_dropdown_categories(array(
                    'show_option_all' => __('Todas as categorias'),
                    'taxonomy' => 'categoria',
                    'name' => 'categoria',
                    'selected' => isset($_GET[$tax_obj->query_var]) ? $_GET[$tax_obj->query_var] : null,
                    'orderby' => 'term_order',
                    'show_count' => false,
                    'hide_empty' => false
                
                ));
            }
            if($tax_slug == 'unidade'){
                wp_dropdown_categories(array(
                    'show_option_all' => __('Todas as unidades'),
                    'taxonomy' => 'unidade',
                    'name' => 'unidade',
                    'selected' => isset($_GET[$tax_obj->query_var]) ? $_GET[$tax_obj->query_var] : null,
                    'orderby' => 'term_order',
                    'show_count' => false,
                    'hide_empty' => false
                
                ));
            }
        }
    }
}

add_action('restrict_manage_posts', 'my_restrict_manage_posts');


function my_convert_restrict($query) {
    global $pagenow;
    global $typenow;
    if ($pagenow == 'edit.php') {
        $filters = get_object_taxonomies($typenow);
        foreach ($filters as $tax_slug) {
            $var =& $query->query_vars[$tax_slug];
            
            if (isset($var) && $var != 0) {
                $term = get_term_by('id', $var, $tax_slug);
                $var  = $term->slug;
            }
            
        }
    }
}

add_filter('parse_query', 'my_convert_restrict');
