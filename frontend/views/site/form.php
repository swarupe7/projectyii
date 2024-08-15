<?php 
use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;

$this->title = 'Form';
?>

<div class="site-login">
    <h1><?= Html::encode($this->title) ?></h1>
    <p>Please fill out the following fields to Collect:</p>
    <div class="row">
        <div class="col-lg-5">

            <?php $form = ActiveForm::begin(['id' => 'user-form']); ?>
                
                <?= $form->field($model, 'firstname')->textInput() ?>
                <?= $form->field($model, 'lastname')->textInput() ?>
                <?= $form->field($model, 'number')->textInput() ?>
                <?= $form->field($model, 'email')->textInput() ?>
                  
                <?= $form->field($model, 'gender')->radioList(['male' => 'Male', 'female' => 'Female'], [
                    'item' => function($index, $label, $name, $checked, $value) {
                        $checked = $checked ? 'checked' : '';
                        return "<label><input name='{$name}' type='radio' class='with-gap' value='{$value}' {$checked} /><span>{$label}</span></label>";
                    },
                    'id' => 'gender-id'
                ]) ?>
                <?= $form->field($model, 'study')->dropDownList(['' => 'Select your option', 'B.tech' => 'B.Tech', 'M.tech' => 'M.Tech', 'XII' => '12th'], ['id' => 'study-id']) ?>
                <div class='study-err'></div>
                <label for="country-code">Country Code</label>
                <select id="country-code" name="country-code" class="form-select">
                    <option value="">Select your country</option>
                    <option value="+1">+1 United States</option>
                    <option value="+91">+91 India</option>
                </select>
                <div class='country-err'></div>
                <br>

               
               <?= $form->field($model, 'hobbies')->dropDownList(['singing' => 'Singing', 'dancing' => 'Dancing', 'playing' => 'Playing', 'Gardening' => 'Gardening'], ['multiple' => true, 'id' => 'hobby-id', 'class' => "js-example-basic-multiple form-select hobbies-class"]) ?>
                <div class='hobbies-err'></div>
                
               
                <br><br>
                <div class="form-group">
                    <div class="col-lg-offset-1 col-lg-11">
                        <?= Html::button('Submit', ['class' => 'btn btn-primary', 'id' => 'submit']) ?>
                    </div>
                </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $('.js-example-basic-multiple').select2();

        function isFormValid() {
            let isValid = true;

            

            if ($('#formusers-firstname').val() === '') {
                isValid = false;
                $('#formusers-firstname').css('border', '1px solid #dc3545');
            } else {
                $('#formusers-firstname').css('border', '');
            }

            if ($('#formusers-lastname').val() === '') {
                isValid = false;
                $('#formusers-lastname').css('border', '1px solid #dc3545');
            } else {
                $('#formusers-lastname').css('border', '');
            }

            if ($('#formusers-email').val() === '') {
                isValid = false;
                $('#formusers-email').css('border', '1px solid #dc3545');
            } else {
                $('#formusers-email').css('border', '');
            }

            if ($('#formusers-number').val() === '') {
                isValid = false;
                $('#formusers-number').css('border', '1px solid #dc3545');
            } else {
                $('#formusers-number').css('border', '');
            }

            if ($('#study-id').val() === '') {
                isValid = false;
                $('#study-id').css('border', '1px solid #dc3545');
                $('.study-err').text('Study cannot be blank.').css('color', '#dc3545');
            } else {
                $('#study-id').css('border', '');
                $('.study-err').text('');
            }

            if ($('#country-code').val() === '') {
                isValid = false;
                $('#country-code').css('border', '1px solid #dc3545');
                $('.country-err').text('Country code cannot be blank.').css('color', '#dc3545');
            } else if ($('#country-code').val() === '+91' && $('#formusers-number').val().length !== 10) {
                isValid = false;
                $('#country-code').css('border', '1px solid #dc3545');
                $('.country-err').text('Code +91 should have 10 digits in number.');
            } else if ($('#country-code').val() === '+1' && $('#formusers-number').val().length !== 11) {
                isValid = false;
                $('#country-code').css('border', '1px solid #dc3545');
                $('.country-err').text('Code +1 should have 11 digits in number.');
            } else {
                $('#country-code').css('border', '');
                $('.country-err').text('');
            }

            if (!($('#gender-id input[type="radio"]:checked').length > 0)) {
                isValid = false;
                $('#gender-id').find('span').css('color', '#dc3545');
            } else {
                $('#gender-id').find('span').css('color', '');
            }

            if ($('#hobby-id').val().length === 0) {
                isValid = false;
                $('#hobby-id').next('.select2-container').css('border', '1px solid #dc3545');
            } else {
                $('#hobby-id').next('.select2-container').css('border', '');
            }

             
            

            return isValid;
        }

        $('#user-form').change(function () {
            isFormValid();
        });

        $('#submit').click(function(event) {
            event.preventDefault();

            if (isFormValid()) { 
                $('#submit').text('Loading...');
                $('#submit').attr('disabled', true);

                const url = $("#user-form").attr('action');
                const data = $("#user-form").serialize();
                
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: data,
                    success: function(response) {
                        alert('Form submitted successfully!');
                        $('#user-form')[0].reset();
                        $('.select2-selection__rendered').empty();
                        $('#submit').attr('disabled', false);
                        $('#submit').text('Submit');
                    },
                    error: function(xhr, status, error) {
                        alert('An error occurred: ' + error);
                        $('#submit').attr('disabled', false);
                        $('#submit').text('Submit');
                    }
                });
            } else {
                alert('Please fill all the fields properly.');
            }
        });
    });
</script>
