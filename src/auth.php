<?php
/**
 * Register a user
 *
 * @param string $email
 * @param string $username
 * @param string $password
 * @param bool $is_admin
 * @return bool
 */
function register_user(string $email, string $username, string $password, bool $is_admin = false): bool
{
    $sql = 'INSERT INTO users(username, email, password, is_admin)
VALUES(:username, :email, :password, :is_admin)';

    $statement = db()->prepare($sql);

    $statement->bindValue(':username', $username, PDO::PARAM_STR);
    $statement->bindValue(':email', $email, PDO::PARAM_STR);
    $statement->bindValue(':password', password_hash($password, PASSWORD_BCRYPT), PDO::PARAM_STR);
    $statement->bindValue(':is_admin', (int)$is_admin, PDO::PARAM_INT);


    return $statement->execute();
}

function add_update_currency(array $currencies): void
{
    foreach ($currencies as $currency) {
        $sql = 'INSERT INTO currency(abbr, quantity, name, value)
VALUES(:abbr, :quantity, :name, :value)
ON DUPLICATE KEY UPDATE
quantity=:quantity, value=:value';
        $statement = db()->prepare($sql);

        $statement->bindValue(':abbr', $currency->abbr, PDO::PARAM_STR);
        $statement->bindValue(':quantity', $currency->quantity, PDO::PARAM_INT);
        $statement->bindValue(':name', $currency->name, PDO::PARAM_STR);
        $statement->bindValue(':value', $currency->value, PDO::PARAM_STR);

        $statement->execute();
    }
}

/**
 * @return array<Currency>
 */
function get_currencies(): array
{
    $sql = 'SELECT * FROM currency';
    $statement = db()->prepare($sql);
    $statement->execute();

    $retvals = $statement->fetchAll();
    $all_currency = array();
    foreach ($retvals as $retval) {
        $currency = new Currency(
            $retval['abbr'],
            $retval['quantity'],
            $retval['name'],
            $retval['value']
        );
        $all_currency[] = $currency;
    }

    return $all_currency;
}

function add_currency(string $value, int $count): bool
{
    $sql = 'INSERT INTO test(value, count)
VALUES(:value, :count)';
    $statement = db()->prepare($sql);
    $statement->bindValue(':value', $value, PDO::PARAM_STR);
    $statement->bindValue(':count', $count, PDO::PARAM_INT);
    return $statement->execute();
}

function find_user_by_username(string $username)
{
    $sql = 'SELECT username, password
            FROM users
            WHERE username=:username';

    $statement = db()->prepare($sql);
    $statement->bindValue(':username', $username, PDO::PARAM_STR);
    $statement->execute();

    return $statement->fetch(PDO::FETCH_ASSOC);
}

function login(string $username, string $password): bool
{
    $user = find_user_by_username($username);

    // if user found, check the password
    if ($user && password_verify($password, $user['password'])) {

        // prevent session fixation attack
        session_regenerate_id();

        // set username in the session
        $_SESSION['username'] = $user['username'];
        $_SESSION['user_id'] = $user['id'];


        return true;
    }

    return false;
}

function is_user_logged_in(): bool
{
    return isset($_SESSION['username']);
}

function require_login(): void
{
    if (!is_user_logged_in()) {
        redirect_to('login.php');
    }
}

function reload(): void
{
    redirect_to('index.php');
}


function logout(): void
{
    if (is_user_logged_in()) {
        unset($_SESSION['username'], $_SESSION['user_id']);
        session_destroy();
        redirect_to('login.php');
    }
}

function current_user()
{
    if (is_user_logged_in()) {
        return $_SESSION['username'];
    }
    return null;
}
