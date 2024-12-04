<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cambio de Contraseña</title>
    <style>
        *{
        border-radius: 10px;
        }
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f7;
             margin: 0;
            padding: 0;
        }

        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }

        .email-header {
            background-color: #28a745;
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 20px 30px;
        }

        .email-header img {
            max-width: 64px;
            max-height: 64px;

            height: auto;
        }

        .email-header h1 {
            font-size: 28px;
            font-weight: 600;
            margin: 0;
            text-align: right;
            color: #047225;
        }

        .email-header h1 span {
            font-weight: normal;
            color: #d48f0f;
        }

        .email-body {
            padding: 30px;
            color: #555555;
            line-height: 1.6;
            font-size: 16px;
        }

        .email-body p {
            margin: 0 0 20px;
        }

        .email-body a {
            display: inline-block;
            background-color: #28a745;
            color: #ffffff;
            text-decoration: none;
            padding: 12px 25px;
            border-radius: 5px;
            font-weight: 600;
            text-align: center;
            transition: background-color 0.3s ease;
        }

        .email-body a:hover {
            background-color: #218838;
        }

        .email-footer {
            text-align: center;
            padding: 20px;
            background-color: #f8f8f8;
            color: #888888;
            font-size: 14px;
        }

        .email-footer a {
            color: #28a745;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s ease;
        }

        .email-footer a:hover {
            color: #218838;
        }

        @media only screen and (max-width: 600px) {
            .email-container {
                padding: 15px;
            }
 
            .email-header h1 {
                font-size: 24px;
            }

            .email-body {
                padding: 20px;
                font-size: 14px;
            }

            .email-body a {
                padding: 10px 20px;
            }
        }
    </style>
</head>

<body>
    <div class="email-container">
        <!-- Header -->
        <div class="email-header">
            <img src="https://i0.wp.com/mapterrabo.com/wp-content/uploads/2023/05/cropped-ISOTIPO-d95eXzP2JLUpDgOD.png" alt="Logo">
            <h1><b>Mapterra</b><span>GO</span></h1>
        </div>

        <!-- Body -->
        <div class="email-body">
            <p>Hola,</p>
            <p>Hemos recibido una solicitud para restablecer tu contraseña. Si hiciste esta solicitud, haz clic en el botón de abajo para cambiar tu contraseña:</p>
            <p>
                <a href="{{ $resetLink }}" target="_blank">Cambiar Contraseña</a>
            </p>
            <p>Si no realizaste esta solicitud, puedes ignorar este correo. Tu contraseña no cambiará hasta que accedas al enlace y elijas una nueva.</p>
            <p>Gracias,</p>
            <p>El equipo de <b>Mapterra</b><span>GO</span></p>
        </div>

        <!-- Footer -->
        <div class="email-footer">
            <p>© 2024 <b>Mapterra</b><span>GO</span> Todos los derechos reservados.</p>
            <p><a href="https://mapterrabo.com" target="_blank">Visítanos</a></p>
        </div>
    </div>
</body>

</html>
