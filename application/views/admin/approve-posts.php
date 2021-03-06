<?php

$this->load->view('admin/header');

foreach($records as $row):
    echo "<article>
    <p class=\"post\" id=\"" . $row->id . "\">" . nl2br($row->content) . "</p>
    <details open>Told by "
    
    . anchor_popup('user/profile/' . $row->user_id . '/' . $row->username, $row->username) .
    
    " at " . $row->added . " (" . timespan(human_to_unix($row->added), now()) . " ago)
        <span>"
        
        . anchor('admin/i_approve/post/' . $row->id, img('img/round_checkmark.png'))
        . anchor('admin/i_disapprove/post/' . $row->id, img('img/round_minus.png'))
        . anchor('admin/later/post/' . $row->id, img('img/round_delete.png')) .
        
        "</span>
    </p>
    </article>";
endforeach;

?>

<script>
    $(document).ready(function() {
        var csrf_token_hash = $.cookie('ci_csrf_token');
        $('.post').editable('<?php echo site_url('admin/edit_post'); ?>',{
            type:'textarea',
            event:'dblclick',
            submit:'OK',
            cancel:'Cancel',
            id:'id',
            name:'content', 
            submitdata:{
                'ci_csrf_token':csrf_token_hash
            },data:function(value,settings){
                var retval = value.replace(/<br[\s\/]?>/gi, '\n');
                return retval;
            }
        });
        
        /*$("#content article").mouseover(function() {
            $(this).find("span").show();
        }).mouseout(function() {
            $(this).find("span").hide();
        });*/
        
        $("#content article details span a").click(function(e) {
            e.preventDefault();
            var a = $(this);
            var linkk = a.attr('href');
            
            $.ajax({
                url: linkk,
                success: function(data) {
                    if(data == 'true')
                    {
                        a.closest("article").slideUp("fast");
                    }
                    if(data == 'empty')
                    {
                        a.closest("article").empty().append("<article>That's all :)</article>");
                        
                    }
                }
            });
        });
    });
    </script>

<?php

$this->load->view('footer');

?>