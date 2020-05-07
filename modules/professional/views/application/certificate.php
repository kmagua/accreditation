<?php

/* @var $this yii\web\View */
/* @var $application app\modules\professional\models\Application */
/* @var $form yii\widgets\ActiveForm */
//$this->title = "Certificate Download";

?>
<div <!--style="background-image: url('<?= Yii::getAlias('@web'. '/images/icta-background-logo.png'); ?>'); background-repeat:no-repeat; background-attachment:fixed; background-size:100% 100%;"--> >
    <div style="width:100%;height:100%;margin-top:-0px;margin-left:-10px;margin-bottom:0px;
        margin-right:-10px;margin:auto;border:solid 4px red;margin-top:49px; ">
        <p class="text-center" style="font-family:'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif;text-align:center; margin-top:-15px;">
            <img src="<?= Yii::getAlias('@web'. '/images/ictalogocert.png'); ?>" style="height:100px" />
        </p>
        <p style="text-align:center;">
            <span style='font-size:28.0pt;line-height:107%'>
            CERTIFICATE OF REGISTRATION
            </span>
        </p>
        <p class='MsoNormal' style='text-align:center'>
            This is to certify that:
        </p>
        <!--<p><img src="~/favicon.ico" style="margin-top:30%;width:9em;height:4em;" /></p> -->
        
        <p style="text-align:center;">
            <span style='font-size:28.0pt;line-height:107%'><?= $application->user->getNames() ?></span>
        </p>
        <br/><br/>
        <p style='text-align:center;'>Is hereby registered
            as …<strong><?= $application->category->name ?></strong>…. Having fulfilled all the requirements of the Government of Kenya
            IT Governance Standard</p><br/>
            
        <p style='text-align:center;'>
            Under Registration Number
        </p><br/>

        <p style='text-align:center;'>
            …… <strong><?= $application->cert_serial ?></strong> ……
        </p><br/>
        <?php $strtotime = strtotime($application->initial_approval_date); ?>
        <p style='text-align:center'>This <?= date('j', $strtotime) ?> <sup>th</sup>
        day <?= date('F', $strtotime) ?> of <?= date('Y', $strtotime) ?></p>        
        
        <br/>
        
        <table>
            <tr>
            <!--<div class="col-md-4" style="float:left;padding-left:49px;"> -->
            <td style="width: 120mm; padding-left: 50px">
                <p style="font-family:Arial;">
                    <img src="<?= Yii::getAlias('@web'. '/images/francis.jpg'); ?>" style="height:100px" />
                    <!-- <img src="@Server.MapPath("~/Views/Profile/sergon.jpg")" style="width:4em;height:2em" />-->
                    <br />
                    Francis Mwaura <br />
                    Head Standards & Processes
                </p>
            </td>
            <td  style="width: 120mm; padding-left:27mm">
                <p style="font-family:Arial; text-align: left">
                 <img src="<?= Yii::getAlias('@web'. '/images/rono1.jpg'); ?>" style="height:100px" />
                    <br />
                    Kipronoh Ronoh P.<br />
                    Director, Programmes and Standards
                </p>
            </td>
        </tr>
        </table>
    </div>    
</div>