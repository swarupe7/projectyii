<?php

use yii\helpers\Html;

$this->title = 'Form';

?>

<head>
   
    <!-- Include Materialize CSS only in this view -->
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
    <!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/habibmhamadi/multi-select-tag@3.0.1/dist/css/multi-select-tag.css"> -->
    
</head>
<body>
<div class="container z-depth-3">
    <form id="infoForm" method="POST" action="<?= \yii\helpers\Url::to(['site/submit-form']) ?>">
        <div class="row">
            <div class="col s12">
                <div class="title">
                    <p class="center-align">INFO FORM</p>
                </div>
            </div>
            <br><br>
            <div class="col s6">
                <div class="input-field">
                    <label for="fname">First Name</label>
                    <input type="text" id="fname" name="fname">
                </div>
                <div class="input-field">
                    <label for="lname">Last Name</label>
                    <input type="text" id="lname" name="lname">
                </div>
                <div class="input-field">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email">
                </div>
            </div>
            <div class="col s6">
                <label for="gender">Gender</label>
                <p>
                    <label>
                        <input name="gender" type="radio" value="male" />
                        <span>Male</span>
                    </label>
                </p>
                <p>
                    <label>
                        <input name="gender" type="radio" value="female" />
                        <span>Female</span>
                    </label>
                </p>
                <p>
                    <label>
                        <input name="gender" type="radio" value="others" />
                        <span>Others</span>
                    </label>
                </p>
            </div>
            <div class="col s6">
                <label for="phone">Phone</label>
                <div class="row">
                    <div class="input-field col s3">
                        <select name="cc" id="cc" class="browser-default">
                            <option value="" disabled selected>Code</option>
                            <option value="india">+91</option>
                            <option value="us">+1</option>
                        </select>
                    </div>
                    <div class="input-field col s9">
                        <input type="number" id="phone" name="phone">
                    </div>
                </div>
            </div>
            <div class="col s6">
                <label for="study">Highest education</label>
                <select name="study" id="study" class="browser-default">
                    <option value="" disabled selected>Choose your option</option>
                    <option value="btech">B.Tech</option>
                    <option value="mtech">M.Tech</option>
                    <option value="xii">12th class</option>
                </select>
            </div>
            <div class="col s6">
                <label for="hobbies">Choose your Hobbies:</label>
                <select multiple name="hobbies[]" id="hobbies">
                    <option value="singing">Singing</option>
                    <option value="dancing">Dancing</option>
                    <option value="playing">Playing</option>
                </select>
            </div>
            <div class="col s12 submit">
                <div class="center-align">
                    <button class="btn waves-effect waves-light" type="submit" id="submit" name="action">Submit</button>
                </div>
            </div>
        </div>
    </form>
</div>
<!-- Include Materialize JS only in this view -->
<script>
    new MultiSelectTag('hobbies')
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
</body>

