<?php

class AuthController extends Controller
{
    public function login(): string
    {
        $error = '';
        $success = '';

        if ($_POST) {
            $email = $this->sanitize_input($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';

            if ($email === '' || $password === '') {
                $error = 'Please fill in all fields.';
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $error = 'Please enter a valid email address.';
            } else {
                $userModel = new User($this->pdo);
                $user = $userModel->getByEmail($email);
                if ($user && password_verify($password, $user['password'])) {
                    if (($user['status'] ?? '') === 'approved') {
                        $_SESSION['user_id'] = $user['id'];
                        $_SESSION['user_name'] = $user['full_name'];
                        $_SESSION['user_type'] = $user['user_type'];
                        header('Location: dashboard.php');
                        exit;
                    } else {
                        $error = 'Your account is pending approval. Please wait for admin approval.';
                    }
                } else {
                    $error = 'Invalid email or password.';
                }
            }
        }

        return $this->view('auth/login', [
            'error' => $error,
            'success' => $success,
        ]);
    }

    private function sanitize_input(string $data): string
    {
        $data = trim($data);
        $data = stripslashes($data);
        return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    }
}

?>

