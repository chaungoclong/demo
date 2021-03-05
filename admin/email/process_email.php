<?php
//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
require '../../dist/email/vendor/autoload.php';
require_once '../../common.php';

//Instantiation and passing `true` enables exceptions

function sendEmail($toEmail, $toName, $subject, $content) {
    $mail = new PHPMailer(true);
    $mail->CharSet = "UTF-8";

    try {
        //Server settings
        $mail->SMTPDebug = 4;                      //Enable verbose debug output
        $mail->isSMTP();                                            //Send using SMTP
        $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
        $mail->Username   = 'tesymail0@gmail.com';                     //SMTP username
        $mail->Password   = 'traiDat4';                               //SMTP password
        $mail->SMTPSecure = 'ssl';         //Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
        $mail->Port       = 465;                                    //TCP port to connect to, use 465 for 

        //Recipients
        $mail->setFrom('tesymail0@gmail.com', 'LONG KK');
        $mail->addAddress($toEmail, $toName);     //Add a recipient

        $mail->addReplyTo('tesymail0@gmail.com', 'Information');
        $mail->addCC('tesymail0@gmail.com');
        $mail->addBCC('tesymail0@gmail.com');



        //Content
        $mail->isHTML(true);                                  //Set email format to HTML
        $mail->Subject = $subject;
        $mail->Body    = $content;
        $mail->AltBody = $content;

        $mail->send();
        echo 'Gửi mail thành công';
    } catch (Exception $e) {
        echo "Không thể gửi mail. Lỗi: {$mail->ErrorInfo}";
    }
}

if(!empty($_POST['action']) && $_POST['action'] == 'send_email_new_order') {
    $orderID = $_POST['orderID'];
    $customerID = $_POST['customerID'];
    $customerInfo = get_user_by_id($customerID);
    $orderInfo = getOrderDetailByID($orderID);
    $toEmail = $customerInfo['cus_email'];
    $toName = $customerInfo['cus_name'];

    $subject = "TIẾP NHẬN ĐƠN HÀNG";

    $content = '  
        <h1>Xin chào ' .$toName. '</h1>
        <p>Cảm ơn bạn đã mua hàng tại cửa hàng của chúng tôi!!!</p>
        <p>Đơn hàng của bạn đã được tiếp nhận, chúng tôi sẽ xử lý đơn hàng của bạn trong thời gian ngắn nhất</p>

        <h2>MÃ ĐƠN HÀNG: '.$orderID.'</h2>
        <h2>ĐƠN HÀNG CHI TIẾT:</h2>
        <table border="1px">
            <thead>
                <tr>
                    <th>SẢN PHẨM</th>
                    <th>GIÁ</th>
                    <th>SỐ LƯỢNG</th>
                    <th>TỔNG TIỀN</th>
                </tr>
            </thead>
            <tbody>
    ';


    foreach ($orderInfo as $key => $oneProduct) {
        $content .= '  
        <tr>
            <td>'.$oneProduct["pro_name"].'</td>
            <td>'.number_format($oneProduct["price"]).'</td>
            <td>'.$oneProduct['amount'].'</td>
            <td>'.number_format($oneProduct["amount"] * $oneProduct["price"]).'</td>
        </tr> 
        ';
    }

    $content .= '  
    <tr>
        <td colspan="3" align="right">TOTAL:</td>
        <td>'.number_format(getTotalMoneyAnOrder($orderID)).'</td>
    </tr>
    <tbody>
    </table> 
    ';
    sendEmail($toEmail, $toName, $subject, $content);
}

if(!empty($_POST['action']) && $_POST['action'] == 'email_ad_confirm_order') {
    $orderID      = $_POST['orderID'];
    $customerID   = s_cell('SELECT cus_id from db_order where or_id = ?', [$orderID], 'i');
    //echo $customerID;
    $customerInfo = get_user_by_id($customerID);
    $orderInfo    = getOrderDetailByID($orderID);
    $toEmail      = $customerInfo['cus_email'];
    $toName       = $customerInfo['cus_name'];
    
    $subject      = "ĐƠN HÀNG ĐÃ ĐƯỢC DUYỆT";

    $content = '  
        <h1>Xin chào ' .$toName. '</h1>
        <p>Cảm ơn bạn đã mua hàng tại cửa hàng của chúng tôi!!!</p>
        <p>Đơn hàng của bạn đã được duyệt, chúng tôi sẽ giao hàng cho bạn trong thời gian ngắn nhất</p>

        <h2>MÃ ĐƠN HÀNG: '.$orderID.'</h2>
        <h2>ĐƠN HÀNG CHI TIẾT:</h2>
        <table border="1px">
            <thead>
                <tr>
                    <th>SẢN PHẨM</th>
                    <th>GIÁ</th>
                    <th>SỐ LƯỢNG</th>
                    <th>TỔNG TIỀN</th>
                </tr>
            </thead>
            <tbody>
    ';


    foreach ($orderInfo as $key => $oneProduct) {
        $content .= '  
        <tr>
            <td>'.$oneProduct["pro_name"].'</td>
            <td>'.number_format($oneProduct["price"]).'</td>
            <td>'.$oneProduct['amount'].'</td>
            <td>'.number_format($oneProduct["amount"] * $oneProduct["price"]).'</td>
        </tr> 
        ';
    }

    $content .= '  
    <tr>
        <td colspan="3" align="right">TOTAL:</td>
        <td>'.number_format(getTotalMoneyAnOrder($orderID)).'</td>
    </tr>
    <tbody>
    </table> 
    ';
    sendEmail($toEmail, $toName, $subject, $content);
}


if(!empty($_POST['action']) && $_POST['action'] == 'email_ad_cancel_order') {
    $orderID      = $_POST['orderID'];
    $customerID   = s_cell('SELECT cus_id from db_order where or_id = ?', [$orderID], 'i');
    //echo $customerID;
    $customerInfo = get_user_by_id($customerID);
    $orderInfo    = getOrderDetailByID($orderID);
    $toEmail      = $customerInfo['cus_email'];
    $toName       = $customerInfo['cus_name'];
    
    $subject      = "ĐƠN HÀNG ĐÃ ĐƯỢC HỦY";

    $content = '  
        <h1>Xin chào ' .$toName. '</h1>
        <p>Cảm ơn bạn đã mua hàng tại cửa hàng của chúng tôi!!!</p>
        <p>Đơn hàng của bạn đã được hủy</p>

        <h2>MÃ ĐƠN HÀNG: '.$orderID.'</h2>
        <h2>ĐƠN HÀNG CHI TIẾT:</h2>
        <table border="1px">
            <thead>
                <tr>
                    <th>SẢN PHẨM</th>
                    <th>GIÁ</th>
                    <th>SỐ LƯỢNG</th>
                    <th>TỔNG TIỀN</th>
                </tr>
            </thead>
            <tbody>
    ';


    foreach ($orderInfo as $key => $oneProduct) {
        $content .= '  
        <tr>
            <td>'.$oneProduct["pro_name"].'</td>
            <td>'.number_format($oneProduct["price"]).'</td>
            <td>'.$oneProduct['amount'].'</td>
            <td>'.number_format($oneProduct["amount"] * $oneProduct["price"]).'</td>
        </tr> 
        ';
    }

    $content .= '  
    <tr>
        <td colspan="3" align="right">TOTAL:</td>
        <td>'.number_format(getTotalMoneyAnOrder($orderID)).'</td>
    </tr>
    <tbody>
    </table> 
    ';
    sendEmail($toEmail, $toName, $subject, $content);
}

if(!empty($_POST['action']) && $_POST['action'] == 'email_cus_cancel_order') {
    $orderID      = $_POST['orderID'];
    $customerID   = s_cell('SELECT cus_id from db_order where or_id = ?', [$orderID], 'i');
    //echo $customerID;
    $customerInfo = get_user_by_id($customerID);
    $orderInfo    = getOrderDetailByID($orderID);
    $toEmail      = $customerInfo['cus_email'];
    $toName       = $customerInfo['cus_name'];
    
    $subject      = "ĐƠN HÀNG ĐÃ ĐƯỢC HỦY";

    $content = '  
        <h1>Xin chào ' .$toName. '</h1>
        <p>Cảm ơn bạn đã mua hàng tại cửa hàng của chúng tôi!!!</p>
        <p>Yêu cầu hủy đơn hàng của bạn đã được chấp nhận</p>

        <h2>MÃ ĐƠN HÀNG: '.$orderID.'</h2>
        <h2>ĐƠN HÀNG CHI TIẾT:</h2>
        <table border="1px">
            <thead>
                <tr>
                    <th>SẢN PHẨM</th>
                    <th>GIÁ</th>
                    <th>SỐ LƯỢNG</th>
                    <th>TỔNG TIỀN</th>
                </tr>
            </thead>
            <tbody>
    ';


    foreach ($orderInfo as $key => $oneProduct) {
        $content .= '  
        <tr>
            <td>'.$oneProduct["pro_name"].'</td>
            <td>'.number_format($oneProduct["price"]).'</td>
            <td>'.$oneProduct['amount'].'</td>
            <td>'.number_format($oneProduct["amount"] * $oneProduct["price"]).'</td>
        </tr> 
        ';
    }

    $content .= '  
    <tr>
        <td colspan="3" align="right">TOTAL:</td>
        <td>'.number_format(getTotalMoneyAnOrder($orderID)).'</td>
    </tr>
    <tbody>
    </table> 
    ';
    sendEmail($toEmail, $toName, $subject, $content);
}
