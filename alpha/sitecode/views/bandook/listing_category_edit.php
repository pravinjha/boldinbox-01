<div class="tblheading">Edit Listing Category</div>
<div id="messages" style="color:#FF0000;">
<?php
// display all messages

if (is_array($messages)):
    foreach ($messages as $type => $msgs):
        foreach ($msgs as $message):
            echo ('<span class="' .  $type .'">' . $message . '</span>');
        endforeach;
    endforeach;
endif;
?>
</div>  
<?php 


echo form_open('bandook/listing_category/category_edit/'.$category['listing_category_id'], array('id' => 'frmCategoryEdit'));
echo '<table class="tbl_forms"><tr><td >';

echo '<div style="color:#FF0000;">'.validation_errors().'</div>'; 
echo "</td></td>";
echo "<tr><td>";
echo "Listing Category Title</td><td>"; 
echo form_input(array('name'=>'listing_category_title','id'=>'listing_category_title','maxlength'=>50 ,'value'=>$category['listing_category_title'])) ."</td></tr>";

echo "<tr><td>Status</td><td>"; 
echo form_dropdown('listing_category_status',array('1'=>'Active','0'=>'Inactive'),$category['listing_category_status']);
echo "</td></tr>";

echo '<tr><td><br>';


echo form_submit(array('name' => 'btnEdit', 'id' => 'btnEdit','class'=>'inputbuttons','content' => 'edit'), 'Submit');
echo '&nbsp;';
echo form_button(array('name'=>'btnCancel','class'=>'inputbuttons', 'value'=>'Cancel','content'=>'Cancel','onclick'=>"window.location.href='".base_url().'bandook/listing_category/category_list'."'"));
echo "</td></tr>";
echo form_hidden('action','submit');
echo form_hidden('listing_category_id',$category['listing_category_id']);
echo form_close();
echo "</table>";
?>
</div>