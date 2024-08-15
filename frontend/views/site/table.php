<?php
use yii\helpers\Html;
use yii\widgets\LinkPager;
use yii\widgets\ActiveForm;

$this->title = 'Your Data';

?>

<div class="your-controller-index">
    <h1><?= Html::encode($this->title) ?></h1>
    <?php $form1 = ActiveForm::begin(['id' => 'search-form']); ?>

     
       <div class="row">
        <div class="col s6">
            
       <?= HTML::textInput('Search',null,["id"=>"search-input"]) ?>
        
        </div>
        
        <div class="col s6 ">

        <?= Html::button('Submit',['class' => 'submit btn btn-primary'])?>
        </div>


       </div>
        
        
    <?php ActiveForm::end() ?>


    <table class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>FirstName</th>
                <th>LastName</th>
                <th>Number</th>
                <th>Gender</th>
                <th>Email</th>
                <th>Study</th>
                <th>Hobbies</th>
                <th>Update</th>
                <th>Delete</th>
               
            </tr>
        </thead>
        <tbody>
           
            <?php foreach ($models as $model): ?>
                <tr id="<?= $model->id?>" data-id="<?= $model->id?>">
                   
                    <td><?= Html::encode($model->firstname) ?></td>
                    <td><?= Html::encode($model->lastname) ?></td>
                    <td><?= Html::encode($model->number) ?></td>
                    <td><?= Html::encode($model->gender) ?></td>
                    <td><?= Html::encode($model->email) ?></td>
                    <td><?= Html::encode($model->study) ?></td>
                    <td><?= Html::encode(str_replace(',', ' ', $model->hobbies)) ?></td>
                    <td><?= Html::button('update',['data-id'=>"$model->id",'class' => 'btn  blue darken-1 update']) ?></td>
                    <td><?= Html::button('delete',['data-id'=>"$model->id",'class' => 'btn btn-danger red darken-1 delete']) ?></td>

                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<div class="center-align" style='margin-left:43%; margin-right:44%;' >
<?=LinkPager::widget(['pagination'=>$pagination]); ?>
</div>

<?php

?>  


<div id="update-modal" class="modal fade" tabindex="-1" role="dialog" style="margin-top: 7em";>
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Update User</h5> 
            </div>
        
            <div class="modal-body">
                <?php $form = ActiveForm::begin(['id' => 'update-form']);  ?>

                    <?= $form->field($model, 'firstname')->textInput() ?>
                    <?= $form->field($model, 'lastname')->textInput() ?>
                    <?= $form->field($model, 'number')->textInput() ?>
                    <?= $form->field($model, 'gender')->dropDownList(['male' => 'Male', 'female' => 'Female']) ?>
                    <?= $form->field($model, 'email')->textInput() ?>
                    <?= $form->field($model, 'study')->textInput() ?>
                    <?= $form->field($model, 'hobbies')->textInput() ?>
                <?php ActiveForm::end(); ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="save-updates">Save changes</button>
                <em></em>
                <button type="button" class="btn btn-secondary" id="close" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>





<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script>
    $(document).ready(function(){

        var id ;

        $('#close').click(function(){
            $('#update-modal').modal('hide');
        });


        $('.submit').click(function(){
            $val=$('#search-input').val();
            // $.ajax({
            //     url:'index.php?r=site/search&name='+$val,
            //     type:'GET',
            //     success:function(response){
            //        if(response.success){
            //         console.log('search success');
            //        }else{
            //         alert('search failed');
            //        }
            //     }

            // })
            $.post('index.php?r=site/table',{"type":1,"name":$val},function(res){
                //console.log("this is the response"+res);
                document.body.parentNode.innerHTML = res;
            })
        })



        $('.update').click(function() {
            id = $(this).data('id');
            
            $.ajax({
            url: 'index.php?r=site/fetch&id=' + id,
            type: 'GET',
            success: function(response) {
               
                if (response.success) {
                    console.log(response.message);  
                $('#formusers-firstname').val(response.message.firstname || '');
                 $('#formusers-lastname').val(response.message.lastname || '');
                $('#formusers-email').val(response.message.email || '');
                $('#formusers-number').val(response.message.number || '');
                $('#formusers-study').val(response.message.study || '');
                $('#formusers-hobbies').val(response.message.hobbies || '');
                $('#formusers-gender').val(response.message.gender || '');
              

                    $('#update-modal').modal('show');



                } else {
                    alert('fetch Failed:  ' + data.message);
                }
            }
        });

    });

    // Handle save updates button click
    $('#save-updates').click(function() {
        var form = $('#update-form');
        var data=form.serialize();
       // console.log(data);
        $.ajax({
            url: 'index.php?r=site/update&id=' + id,
            type: 'POST',
            data: data,
            success: function(response) {
                var data = JSON.parse(response);
                if (data.success) {
                    alert('Update Successful');
                    $('#update-modal').modal('hide');
                    location.reload(); 
                } else {
                    alert('Update Failed:  ' + data.message);
                }
            }
        });
    });



       $('.delete').click(function(){
            val=$(this).data('id');
           // console.log(val);
            $.ajax({
                    url: 'index.php?r=site/delete',
                    type: 'POST',
                    data: {'id':val},
                    success: function(response) {
                        alert('Delete Successful');
                        $('#'+val).remove();
                       
                    },
                    error: function(xhr, status, error) {
                        alert('Not deleted' + error);
                    }
                });
        });    
    });
</script>