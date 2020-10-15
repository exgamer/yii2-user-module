<?php

namespace concepture\yii2user\services\interfaces;

use concepture\yii2user\forms\ChangePasswordForm;
use concepture\yii2user\forms\CredentialConfirmForm;
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
    public function confirmCredential(CredentialConfirmForm $form);
    public function sendPasswordResetEmail(EmailPasswordResetRequestForm $form);
    public function resetPassword(PasswordResetForm $form);
    public function changePassword(ChangePasswordForm $form);
    public function onSocialAuthSuccess($client);
    public function login($user, $duration = null);
}