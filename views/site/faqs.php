<?php
use yii\jui\Accordion;
$this->title = 'FAQs';
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<?php
echo Accordion::widget([
    'items' => [
        [
            'header' => '1. What is accreditation of ICT service providers?',
            'content' => 'Accreditation is the formal recognition of an ICT service provider by ICT Authority of the competence to work to specified standards.',
        ],
        [
            'header' => '2. Why should supplier be accredited by ICTA?',
            'content' => 'To ensure that public agencies procure ICT services only from reputable, qualified and approved provider in order to:
                <ul><li>Optimize value (cost and ROI)</li>
                <li>Assure quality</li>
                <li>To maintain expected level of performance in delivery of their services to government.</li></ul>'
        ],
        [
            'header' => '3. What are the Accreditation requirements?',
            'content' => 'Requirements for application for accreditation are provided in the Appendix 33 of the IT Governance standard. <br>
                Kindly attach the following documents;
                <ul><li>Company profile</li> 
                <li>Business registration (certificate of incorporation, partnership deed) </li>
                <li>Companies Act/ business permit</li>
                <li>KRA compliance certificate</li>
                <li>Copies of ID, KRA pin for directors </li>
                <li>CVs, academic certificates, professional and project management certificates for directors and technical staff</li>
                <li>Evidence of related previous work done including LPOs, LSOs, Contracts, recommendation Letters and other testimonials</li>
                <li>Bank statement and audited accounts of most current 3 years </li></ul>',
        ],
        [
            'header' => '4. How long does the accreditation process take?',
            'content' => 'Where a firm application is submitted in form and order stipulated in the standard, the accreditation processes, will be completed within four (4) weeks.
            <br><br>Incase an applicant does not fully comply with requirements set by the standard, the Authority shall inform the applicant of the rejection within thirty (30) days of the application giving reasons for such decision.'
        ],
        [
            'header' => '5. Under which categories are Government ICT suppliers accredited?',
            'content' => 'Currently, we are accrediting suppliers under the following ICT domain areas defined in accordance to GOK ICT standards;
                <ul><li>End User Computing Devices</li>
                <li>ICT Networks</li>
                <li>Data center</li>
                <li>Systems and Applications</li>
                <li>Information Security</li>
                <li>Electronic Record Management</li> 
                <li>ICT Consultancy</li>
                <li>Cloud computing</li>
                <li>ICT human capacity development</li></ul>',
        ],
        [
            'header' => '6. Why were we accredited on category A and not B, for instance why ICT networks and NOT data center?',
            'content' => 'The categorization of ICT service providers is the on basis of projects undertaken and competencies of company’s technical staff'
        ],
        [
            'header' => '7. What are the various grades under each category?',
            'content' => 'Grading is based on the firm’s experience, proven capacity and competence of the staff as well as other resources. 
<br><br>The grading ranges as follows;
<ul><li>ICTA 1 (highest)</li>
<li>ICTA 2</li>
<li>ICTA 3</li>
<li>ICTA 4</li>
<li>ICTA 5</li>
<li>ICTA 6</li>
<li>ICTA 7</li>
<li>ICTA 8 (lowest)</li></ul>',
        ],
        [
            'header' => '8. Can a supplier grading be reviewed upwards in subsequent accreditation?',
            'content' => 'YES. But elevation is based on objective evidences provided by the firm including proven growth in products and/or services provided, increasing technical staffing etc.'
        ],
        [
            'header' => '9. What are the applicable accreditation charges?',
            'content' => 'The charges are as per Appendix 33, of IT Governance standard 
<br>APPLICABLE ACCREDITATION FEES <br> <table><tr><th>CATEGORY</th><th>REGISTRATION FEE (KES)</th></tr>
<tr><th>ICTA 1</th><th>30,000</th></tr>
<tr><th>ICTA 2</th><th>25,000</th></tr>
<tr><th>ICTA 3</th><th>20,000</th></tr>
<tr><th>ICTA 4</th><th>15,000</th></tr>
<tr><th>ICTA 5</th><th>12,000</th></tr>
<tr><th>ICTA 6</th><th>10,000</th></tr>
<tr><th>ICTA 7</th><th>5000</th></tr>
<tr><th>ICTA 8</th><th>2000</th></tr></table>'
        ],
        [
            'header' => '10. When should payment be made?',
            'content' => 'Payment is should be made prior to picking the certificate; the applicant should remit until the Authority officially communicates on their category and grade, for example ICTA 5: Information Security '
        ],
        [
            'header' => '11. How can one confirm that a supplier is accredited?',
            'content' => 'A valid certificate of accreditation is issued to confirm accreditation. In addition, the authority maintains a register of accredited suppliers on the Standards portal.'
        ],
        [
            'header' => '12. How can I contact ICT Authority on accreditation matters?',
            'content' => '<ul><li>Email us at standards@ict.go.ke</li>  
<li>Call +254 20 2211960</li></ul>'
        ],
    ],
    'options' => ['tag' => 'div'],
    'itemOptions' => ['tag' => 'div'],
    'headerOptions' => ['tag' => 'h5'],
    'clientOptions' => ['collapsible' => false,
        'autoHeight' => false,
        'heightStyle' => 'content'
    ],
]);

