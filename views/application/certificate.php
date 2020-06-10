<?php

/* @var $this yii\web\View */
/* @var $application app\models\Application */
/* @var $form yii\widgets\ActiveForm */
//$this->title = "Certificate Download";

?>
<div>
    <div style="width:100%;height:100%;margin-top:-0px;margin-left:-10px;margin-bottom:0px;margin-right:-10px;margin:auto;border:solid 4px red;margin-top:49px;">
        <p class="text-center" style="font-family:'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif;text-align:center; margin-top:-15px;">
            <img src="<?= Yii::getAlias('@web'. '/images/ictalogocert.png'); ?>" style="height:100px" />
        </p>
        <p style="text-align:center;font-family:'Old English Text MT';color:gray;font-weight:bold;font-size:3em;margin-top:-80px;">
            Certificate of Accreditation
        </p>
        <p style="text-align:center;font-family:Arial;color:gray;font-size:2em;">
            This is to certify that:
        </p>
        <!--<p><img src="~/favicon.ico" style="margin-top:30%;width:9em;height:4em;" /></p> -->
        
        <p style="text-align:center;font-family:Arial;color:gray;font-weight:100;text-transform:uppercase;text-decoration:underline;font-size:1.8em;"><b>
                <?= $application->company->company_name ?> &nbsp; (<?= $application->company->business_reg_no ?>)</b>
            </p>
        
            
                <p style="text-align:center;font-family:Arial;color:gray;font-weight:100;line-height:1.5;font-size:1.7em;">
                    Has demonstrated compliance with the Government IT Governance <br />
                    Standard, Criteria for accreditation of Government ICT <br />
                    Suppliers/Contractors and has been accredited under 
                    Category<br/> <b><span style="text-decoration:underline; text-decoration-style:dashed;"><?= $app_class->classification . ": " . strtoupper($application->accreditationType->name) ?></span></b> 
                    for the provision of ICT services in the<br/> scope of accreditation commencing
                
                from <b><span style="text-decoration:underline; text-decoration-style:dashed;">&nbsp;&nbsp;&nbsp;&nbsp;
                    <?= date('d-m-Y', strtotime($application->initial_approval_date)) ?>&nbsp;&nbsp;&nbsp;&nbsp;</span></b> to <b>
                    <span style="text-decoration:underline; text-decoration-style:dashed;">&nbsp;&nbsp;&nbsp;&nbsp;
                    <?= date('d-m-Y', strtotime($application->initial_approval_date . "+ 1 year")) ?>&nbsp;&nbsp;&nbsp;&nbsp;</span></b>
            </p>
        
        <br/>
        <br/>
        <br/>
        <br/>
        <table>
            <tr>
            <!--<div class="col-md-4" style="float:left;padding-left:49px;"> -->
            <td style="width: 120mm; padding-left: 50px;">
                <p style="font-family:Arial;color:gray;font-weight:100;font-size:1.5em; ">
                    <img src="<?= Yii::getAlias('@web'. '/images/francis.jpg'); ?>" style="height:100px" />
                    <!-- <img src="@Server.MapPath("~/Views/Profile/sergon.jpg")" style="width:4em;height:2em" />-->
                    <br />
                    Francis Mwaura <br />
                    Chairman, Accreditation Committee
                </p>
            </td>
            <td  style="width: 130mm; float:right !important;">
                <p>&nbsp;</p>
            </td>
            <td  style="width: 120mm; float:right !important;">
                <p style="font-family:Arial;font-weight:100;color:gray;font-size:1.5em; text-align: left">
                 <img src="<?= Yii::getAlias('@web'. '/images/rono1.jpg'); ?>" style="height:100px" />
                    <br />
                    Kipronoh Ronoh P.<br />
                    Director, Programmes and Standards
                </p>
            </td>
        </tr>
        </table>
        </div>
    <div>
</div>
</div>