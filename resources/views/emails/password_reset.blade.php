<!DOCTYPE html>
<html>
  <body>
    <h2>Recuperación de contraseña</h2>
    <p>Hola {{ $user->name ?? 'usuario' }}, recibimos una solicitud para restablecer tu contraseña.</p>
    <p>Haz clic en el siguiente enlace para crear una nueva contraseña (válido por 1 hora):</p>
    <a href="{{ $link }}" style="background-color:#4CAF50;color:white;padding:10px 20px;text-decoration:none;">Restablecer contraseña</a>
    <p>Si no solicitaste esto, ignora este mensaje.</p>
  </body>
</html>
