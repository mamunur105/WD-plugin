<?php

namespace Wd\Ac\Admin;
if (!class_exists('WP_List_Table')) {
    require_once ABSPATH."wp-admin/includes/class-wp-list-table.php";
}
/**
 * List table class
 */
class Address_List extends \WP_List_Table{
	
	  
    // private $_items;

    // function set_data($data){
    //     $this->_items = $data;   
    // }
    function get_columns(){
        return [
            'cb' => '<input type="checkbox" />',
            'name'=>__("Name", 'datatable'),
            'address'=>__('Address', 'datatable'),
            'phone'=>__('Phone', 'datatable'),
            'created_at'=>__('Created At','datatable')
        ];
    }

    function get_shortable_columns(){
        return [
            'created_at'=> ['created_at',true],
            'name'=> ['name',true]
        ];
    }

    function column_cb($item){
        return sprintf(
            '<input type="checkbox" name="address_id[]" value="%d" />', $item->id
        );
    }

    function column_created_at($item){
        return wp_date( get_option( 'date_format' ), strtotime( $item->created_at ) );
    }

    public function column_name( $item ) {
        $actions = [];

        $actions['edit']   = sprintf( '<a href="%s" title="%s">%s</a>', admin_url( 'admin.php?page=wd-ac&action=edit&id=' . $item->id ), $item->id, __( 'Edit', 'wedevs-academy' ), __( 'Edit', 'wdac' ) );
        $actions['delete'] = sprintf( '<a href="%s" class="submitdelete" onclick="return confirm(\'Are you sure?\');" title="%s">%s</a>', wp_nonce_url( admin_url( 'admin-post.php?action=wd-ac-delete-address&id=' . $item->id ), 'wd-ac-delete-address' ), $item->id, __( 'Delete', 'wdac' ), __( 'Delete', 'wdac' ) );

        return sprintf(
            '<a href="%1$s"><strong>%2$s</strong></a> %3$s', admin_url( 'admin.php?page=wd-ac&action=view&id' . $item->id ), $item->name, $this->row_actions( $actions )
        );
    }


    function prepare_items(){
        $paged          = $_REQUEST['paged']??1;
        $total_items    = wd_ac_address_count();
        $per_page       = 20;
        $current_page   = $this->get_pagenum();
        $offset         = ( $current_page - 1 ) * $per_page;
        $args = [
            'number'    => $per_page,
            'offset'    => $offset,
        ];

        if ( isset( $_REQUEST['orderby'] ) && isset( $_REQUEST['order'] ) ) {
            $args['orderby'] = $_REQUEST['orderby'];
            $args['order']   = $_REQUEST['order'] ;
        }


        $this->_column_headers = array($this->get_columns(),array(),$this->get_shortable_columns());
        $this->items    = wd_ac_get_addresses($args);
        $this->set_pagination_args([
            'total_items'   =>$total_items,
            'per_page'      => $per_page,
            'total_pages'   =>ceil($total_items / $per_page) 
        ]);
    }

    function column_default($item,$column_name){
        return $item->$column_name;
    }




}