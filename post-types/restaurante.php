<?php
function create_post_type_restaurante() {

register_taxonomy('cidade', 'restaurante', array(
        'label' => 'Cidade',
        'singular_label' => 'Cidade',
        'hierarchical' => true,
        'query_var' => true,
        'rewrite' => array(
            'slug' => 'cidade'
        )
    ));
     
    $labels = array(
        'name' => __('Restaurante', 'theme'),
        'singular_name' => __('Restaurante', 'theme'),
        'rewrite' => array(
            'slug' => __('restaurantes', 'theme')
        ),
        'add_new' => _x('Adicionar item', 'restaurante', 'theme'),
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
            'slug' => 'restaurantes/%cidade%',
            'with_front' => true
        ),
        'capability_type' => 'post',
        'supports' => array(
            'title',
            'editor',
            'thumbnail'
        ),
        'taxonomies' => array(
            'cidade'
        ),
        'menu_position' => 5,
        'has_archive' => true
    );
    
    register_post_type('restaurante', $args);
    flush_rewrite_rules();
    
}

add_action('init', 'create_post_type_restaurante');

function restaurante_permalink_structure($post_link, $post, $leavename, $sample) {
    
    if (false !== strpos($post_link, '%cidade%')) {
        $terms = get_the_terms($post->ID, 'cidade');
        if (is_array($terms)) {
            $post_link = str_replace('%cidade%', array_pop($terms)->slug, $post_link);
        } else {
            $post_link = str_replace('%cidade%', "", $post_link);
        }
    }
    
    return $post_link;
}

add_filter('post_type_link', 'restaurante_permalink_structure', 10, 4);


function add_restaurante_metaboxes() {
    add_meta_box('infos-restaurante', 'Infos', 'createFieldsRestaurante', 'restaurante', 'normal', 'default');
}

add_action( 'add_meta_boxes', 'add_restaurante_metaboxes' );

function createFieldsRestaurante(){
    global $post;
    
    $rest_cidade = get_post_meta($post->ID, 'rest_cidade', true);
    $rest_telefone = get_post_meta($post->ID, 'rest_telefone', true);
    $rest_email = get_post_meta($post->ID, 'rest_email', true);
    $rest_funcionamento = get_post_meta($post->ID, 'rest_funcionamento', true);
    $rest_endereco = get_post_meta($post->ID, 'rest_endereco', true);
    $rest_unidade = get_post_meta($post->ID, 'rest_unidade', true);
    $rest_cardapio = get_post_meta($post->ID, 'rest_cardapio', true);
    $rest_latitude = get_post_meta($post->ID, 'rest_latitude', true);
    $rest_longitude = get_post_meta($post->ID, 'rest_longitude', true);

    echo '<div class="box-input-restaurante">';
    echo '<label style="display: block;float: left;width: 75px;line-height: 27px;" for="rest_cidade">Cidade:</label><input style="width:200px;margin-bottom:10px;" type="text" id="rest_cidade" name="rest_cidade" value="' . $rest_cidade  . '"/>';
    echo '<div style="clear:both"></div>';

    echo '<label style="display: block;float: left;width: 75px;line-height: 27px;" for="rest_telefone">Telefone:</label><input style="width:200px;margin-bottom:10px;" type="text" id="rest_telefone" name="rest_telefone" value="' . $rest_telefone  . '"/>';
    echo '<div style="clear:both"></div>';

    echo '<label style="display: block;float: left;width: 75px;line-height: 27px;" for="rest_email">Email:</label><input style="width:200px;margin-bottom:10px;" type="text" id="rest_email" name="rest_email" value="' . $rest_email  . '"/>';
    echo '<div style="clear:both"></div>';

    echo '<label style="display: block;float: left;width: 100px;line-height: 27px;" for="rest_funcionamento">Funcionamento:</label><input style="width:200px;margin-bottom:10px;" type="text" id="rest_funcionamento" name="rest_funcionamento" value="' . $rest_funcionamento  . '"/>';
    echo '<div style="clear:both"></div>';

    echo '<label style="display: block;float: left;width: 75px;line-height: 27px;" for="rest_endereco">Endereço:</label><input style="width:200px;margin-bottom:10px;" type="text" id="rest_endereco" name="rest_endereco" value="' . $rest_endereco  . '"/>';
    echo '<div style="clear:both"></div>';

    echo '<label style="display: block;float: left;width: 75px;line-height: 27px;" for="rest_unidade">Unidade:</label><input style="width:200px;margin-bottom:10px;" type="text" id="rest_unidade" name="rest_unidade" value="' . $rest_unidade  . '"/>';
    echo '<div style="clear:both"></div>';

    echo '<label style="display: block;float: left;width: 75px;line-height: 27px;" for="rest_cardapio">Cardápio:</label><input style="width:200px;margin-bottom:10px;" type="text" id="rest_cardapio" name="rest_cardapio" value="' . $rest_cardapio  . '"/>';
    echo '<div style="clear:both"></div>';

    echo '<label style="display: block;float: left;width: 75px;line-height: 27px;" for="rest_latitude">Latitude:</label><input style="width:200px;margin-bottom:10px;" type="text" id="rest_latitude" name="rest_latitude" value="' . $rest_latitude  . '"/>';
    echo '<div style="clear:both"></div>';

    echo '<label style="display: block;float: left;width: 75px;line-height: 27px;" for="rest_longitude">Longitude:</label><input style="width:200px;margin-bottom:10px;" type="text" id="rest_longitude" name="rest_longitude" value="' . $rest_longitude  . '"/>';
    echo '<div style="clear:both"></div>';
    echo '<input type="hidden" name="restaurante_noncename" id="restaurante_noncename" value="' . wp_create_nonce( plugin_basename(__FILE__) ) . '" />';
    echo '</div>';
}

function save_restaurante_meta($post_id, $post) {       
    if(!empty($_POST)){
        if(isset($_POST['restaurante_noncename'])){
            if ( !wp_verify_nonce( $_POST['restaurante_noncename'], plugin_basename(__FILE__) )) {
                return $post->ID;
            }
            if ( !current_user_can( 'edit_post', $post->ID ))
                return $post->ID;

            $events_meta['rest_cidade'] = $_POST['rest_cidade'];
            $events_meta['rest_telefone'] = $_POST['rest_telefone'];
            $events_meta['rest_email'] = $_POST['rest_email'];
            $events_meta['rest_funcionamento'] = $_POST['rest_funcionamento'];
            $events_meta['rest_endereco'] = $_POST['rest_endereco'];
            $events_meta['rest_unidade'] = $_POST['rest_unidade'];
            $events_meta['rest_cardapio'] = $_POST['rest_cardapio'];
            $events_meta['rest_latitude'] = $_POST['rest_latitude'];
            $events_meta['rest_longitude'] = $_POST['rest_longitude'];
            
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

add_action('save_post', 'save_restaurante_meta', 1, 2); 
if (class_exists('MultiPostThumbnails')) {
 
    new MultiPostThumbnails(array(
        'label' => 'Primeira imagem do slide',
        'id' => 'first-slide-restaurant',
        'post_type' => 'restaurante'
    ) );

    new MultiPostThumbnails(array(
        'label' => 'Segunda imagem  do slide',
        'id' => 'second-slide-restaurant',
        'post_type' => 'restaurante'
    ) );
    
    new MultiPostThumbnails(array(
        'label' => 'Terceira imagem do slide',
        'id' => 'third-slide-restaurant',
        'post_type' => 'restaurante'
    ) );

    new MultiPostThumbnails(array(
        'label' => 'Quarta Imagem do slide',
        'id' => 'fourth-slide-restaurant',
        'post_type' => 'restaurante'
    ) ); 
    new MultiPostThumbnails(array(
        'label' => 'Quinta Imagem do slide',
        'id' => 'five-slide-restaurant',
        'post_type' => 'restaurante'
    ) ); 
}
 
