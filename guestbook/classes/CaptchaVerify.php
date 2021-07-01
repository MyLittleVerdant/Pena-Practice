<?php
error_reporting(E_ERROR);
class CaptchaVerify
{

    private $captcha_value = '';
    private $captcha_field = '';
    private $answer_time = '';

    private function session_read()
    {
        session_start();
        
        $this->captcha_value = $_SESSION['captcha_value'];
        $this->captcha_field = $_SESSION['captcha_field'];
        $this->answer_time = $_SESSION['answer_time'];

       
    }

    private function error_msg($message)
    {
        $_SESSION[$_SERVER['REMOTE_ADDR']] ++;
        exit($message);
    }

    public function verify_code()
    {
        $this->session_read();

        $array = array(
            1    => $this->answer_time,
            2    => $this->captcha_field,
            3    => $this->captcha_value,
           
        );
        // echo json_encode($array);
        

        if (isset($_SESSION[$_SERVER['REMOTE_ADDR']]) && $_SESSION[$_SERVER['REMOTE_ADDR']] >= 10)
            echo json_encode('Вы ввели слишком много неверных капчей! Обратитесь за помощью к администратору');

        if (!empty($this->captcha_value) && !empty($this->captcha_field) && !empty($this->answer_time))
        {
            $this->current_time = strtotime(date('d-m-Y H:i:s'));

            if ($this->current_time - $this->answer_time < 3)
                echo json_encode('Вы или робот или вводите капчу слишком быстро!');
            if ($_POST[$this->captcha_field] == '')
                echo json_encode('Робот, уходи!');

            if (md5(md5($_POST[$this->captcha_field])) == $this->captcha_value)
            {
                unset($_SESSION['captcha_value']);
                unset($_SESSION['captcha_field']);
                unset($_SESSION['answer_time']);
                echo json_encode ('OK');

            }
                
            else
                echo json_encode('Неверная капча!');
        }
        else echo json_encode($array);
        
     }
}