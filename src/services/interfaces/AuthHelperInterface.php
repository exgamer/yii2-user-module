<?php

namespace concepture\yii2user\services\interfaces;

use concepture\yii2user\forms\EmailPasswordResetRequestForm;
use concepture\yii2user\forms\PasswordResetForm;
use concepture\yii2user\forms\SignInForm;
use concepture\yii2user\forms\SignUpForm;

/**
 * Interface AuthHelperInterface
 * @package concepture\yii2handbook\services\interfaces
 */
interface AuthHelperInterface
{
    public function signUp(SignUpForm $form);
    public function signIn(SignInForm $form);
    public function sendPasswordResetEmail(EmailPasswordResetRequestForm $form);
    public function changePassword(PasswordResetForm $form);
}