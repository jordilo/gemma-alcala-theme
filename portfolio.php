<?php
function create_post_type()
{
    register_post_type('portfolio',
        array(
            'labels' => array(
                'name' => __('Portfolio'),
                'singular_name' => __('Portfolio'),
            ),
            'public' => true,
            'has_archive' => true,
            // 'taxonomies' => array( 'portfolio_categories'),
            'supports' => array('title', 'editor', 'thumbnail', 'topics'),
            'register_meta_box_cb' => 'cd_meta_box_add',
        )
    );
    register_post_type('lighting',
        array(
            'labels' => array(
                'name' => __('Lighting'),
                'singular_name' => __('Lighting'),
            ),
            'public' => true,
            'has_archive' => true,
            // 'taxonomies' => array( 'portfolio_categories'),
            'supports' => array('title', 'editor', 'thumbnail', 'topics'),
            'register_meta_box_cb' => 'cd_meta_box_add_lighting',
        )
    );

}
add_action('init', 'create_post_type');
add_action('add_meta_boxes', 'cd_meta_box_add_post');

function cd_meta_box_add()
{
    add_meta_box('my-meta-box-id', __('Gallery'), 'cd_meta_box_cb', 'portfolio', 'normal', 'high');
    add_meta_box('item-order-priority', __('Order Priority'), 'item_order_priority', 'portfolio', 'normal', 'high');
}
function cd_meta_box_add_lighting()
{
    add_meta_box('my-meta-box-id-2', __('Gallery'), 'cd_meta_box_cb', 'lighting', 'normal', 'high');
    add_meta_box('item-order-priority', __('Order Priority'), 'item_order_priority', 'lighting', 'normal', 'high');
}
function cd_meta_box_add_post()
{
    add_meta_box('my-meta-box-id-2', __('Gallery'), 'cd_meta_box_cb', 'post', 'normal', 'high');
    add_meta_box('item-order-priority', __('Order Priority'), 'item_order_priority', 'post', 'normal', 'high');
}

function item_order_priority()
{
    global $post;
    wp_nonce_field('portfolio_nonce', 'meta_box_nonce');
    $values = get_post_meta($post->ID, 'item-order-priority');
    ?>
    <input id="item-order-priority" type="number" name="item_order_priority" value="<?=$values[0]?>"/>

<?php }

function cd_meta_box_cb()
{?>
<?php
global $post;

    wp_nonce_field('portfolio_nonce', 'meta_box_nonce');
    $values = get_post_custom($post->ID);

    $images = isset($values['portfolio_gallery']) ? $values['portfolio_gallery'][0] : '';

    ?>
<input id="portfolio_gallery_value" type="hidden" name="portfolio_gallery" value="<?=$images?>"/>
<input id="portfolio_gallery_button" type="button" value="Add Gallery" />
<div id="portfolio-gallery" class="clearfix">
 <?php if (count($images)) {?>
  <?php $ids = explode(",", $images)?>
  <ul id="sortable" class="clearfix">
  <?php foreach ($ids as $id) {?>
    <li>
      <span class="shift8_portfolio_gallery_container">
        <span class="shift8_portfolio_gallery_close">
        <img class="portfolio-gallery-image" id="<?=$id?>" src="<?=wp_get_attachment_thumb_url($id)?>">
        </span>
      </span>
    </li>
    <?php }?>
  </ul>
 <?php }?>
</div>
<?php }

function my_admin_scripts()
{

    wp_enqueue_script('media-upload');
    wp_enqueue_script('thickbox');
    wp_register_script('my-upload', get_template_directory_uri() . '/js/upload-gallery.js', array('jquery', 'media-upload', 'thickbox'));
    wp_enqueue_script('my-upload');
}

function my_admin_styles()
{
    wp_enqueue_style('shift8_portfolio_admin_css', get_template_directory_uri() . '/css/portfolio_admin.css');
    wp_enqueue_style('thickbox');
}

add_action('admin_print_scripts', 'my_admin_scripts');
add_action('admin_print_styles', 'my_admin_styles');

add_action('save_post', 'cd_meta_box_save');
function cd_meta_box_save($post_id)
{

    // Bail if we're doing an auto save
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    // if our nonce isn't there, or we can't verify it, bail
    if (!isset($_POST['meta_box_nonce']) || !wp_verify_nonce($_POST['meta_box_nonce'], 'portfolio_nonce')) {
        return;
    }

    // if our current user can't edit this post, bail
    if (!current_user_can('edit_post')) {
        return;
    }

    if (isset($_POST['portfolio_gallery'])) {
        update_post_meta($post_id, 'portfolio_gallery', wp_kses($_POST['portfolio_gallery'], array()));
    }
    if (isset($_POST['item_order_priority'])) {
        update_post_meta($post_id, 'item-order-priority', wp_kses($_POST['item_order_priority'], array()));
    }

}

function order_posts_by_title($query){
    $post_type  = $query->query_vars['post_type'];
    $isPostTypeSortable =  $post_type == "portfolio" || $post_type == "lighting" /*|| $post_type == ""*/;
    if ($query->is_main_query() & $isPostTypeSortable) {
        $query->set('orderby', 'meta_value_num');
        $query->set('meta_key', 'item-order-priority');
        $query->set('order', 'DESC');
    }
}
add_action('pre_get_posts', 'order_posts_by_title');
?>