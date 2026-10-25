<?php
require __DIR__.'/config.php';
if (current_user()) redirect('index.php');
$error='';
if ($_SERVER['REQUEST_METHOD']==='POST') {
  check_csrf();
  $action=$_POST['action'] ?? 'login';
  $email=strtolower(trim($_POST['email'] ?? ''));
  $password=$_POST['password'] ?? '';
  try {
    if ($action==='register') {
      $name=trim($_POST['full_name'] ?? '');
      $student=trim($_POST['student_id'] ?? '');
      $domain=strtolower(substr(strrchr($email,'@') ?: '',1));
      if ($name==='' || $student==='' || !filter_var($email,FILTER_VALIDATE_EMAIL) || !in_array($domain,ALLOWED_EMAIL_DOMAINS,true)) throw new RuntimeException('Use your valid PHINMA email and complete all fields.');
      if (strlen($password)<8) throw new RuntimeException('Password must be at least 8 characters.');
      $stmt=db()->prepare('INSERT INTO users(full_name,student_id,email,password_hash) VALUES(?,?,?,?)');
      $stmt->execute([$name,$student,$email,password_hash($password,PASSWORD_DEFAULT)]);
      session_regenerate_id(true);
      $_SESSION['user']=['id'=>(int)db()->lastInsertId(),'full_name'=>$name,'email'=>$email,'role'=>'student','student_id'=>$student];
      flash('Account created. Welcome!');
      redirect('index.php');
    } else {
      $stmt=db()->prepare('SELECT * FROM users WHERE email=? LIMIT 1'); $stmt->execute([$email]); $u=$stmt->fetch();
      if (!$u || !password_verify($password,$u['password_hash'])) throw new RuntimeException('Email or password is incorrect.');
      session_regenerate_id(true);
      $_SESSION['user']=['id'=>(int)$u['id'],'full_name'=>$u['full_name'],'email'=>$u['email'],'role'=>$u['role'],'student_id'=>$u['student_id']];
      flash('Welcome back!');
      redirect($u['role']==='admin'?'admin.php':'index.php');
    }
  } catch (PDOException $ex) {
    $error = $ex->getCode()==='23000' ? 'That email is already registered.' : 'Database error. Check your database setup.';
  } catch (RuntimeException $ex) { $error=$ex->getMessage(); }
}
$mode=$_GET['mode'] ?? 'login';
?><!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Account · PHINMA AU Canteen</title><link rel="stylesheet" href="style.css"></head><body>
<div class="auth wrap"><div class="panel"><a class="brand" href="auth.php">PHINMA AU CANTEEN</a><h1><?= $mode==='register'?'Create student account':'Sign in' ?></h1><p class="muted">Use your PHINMA email to access campus ordering.</p>
<?php if($error): ?><div class="alert error"><?=e($error)?></div><?php endif; ?>
<form method="post"><input type="hidden" name="csrf" value="<?=e(csrf_token())?>"><input type="hidden" name="action" value="<?= $mode==='register'?'register':'login' ?>">
<?php if($mode==='register'): ?><div class="field"><label>Full name</label><input name="full_name" required maxlength="120" autocomplete="name"></div><div class="field"><label>Student ID</label><input name="student_id" required maxlength="60"></div><?php endif; ?>
<div class="field"><label>PHINMA email</label><input type="email" name="email" required autocomplete="email" placeholder="you@phinmaed.com"></div><div class="field"><label>Password</label><input type="password" name="password" required minlength="<?= $mode==='register'?'8':'1' ?>" autocomplete="<?= $mode==='register'?'new-password':'current-password' ?>"></div>
<button type="submit" style="width:100%"><?= $mode==='register'?'Create account':'Login' ?></button></form>
<p class="muted"><?= $mode==='register'?'Already registered?':'New student?' ?> <a href="auth.php?mode=<?= $mode==='register'?'login':'register' ?>"><?= $mode==='register'?'Sign in':'Create an account' ?></a></p>
<p class="muted">Accepted domains: @phinmaed.com and @phinma.edu.ph</p></div><p class="footer">Demo project. Ask your school administrator which email domain is officially supported.</p></div></body></html>
