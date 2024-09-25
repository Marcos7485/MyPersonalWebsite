<!DOCTYPE html>
<html>

<head>
    <title>Contact</title>
    <style>
        html {
            font-size: 10px;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: black;
            margin: 0;
            padding: 0;
        }

        .banner {
            background-color: #000;
            color: #ffffff;
            text-align: center;
            padding: 2rem 0;
        }

        .banner img {
            position: absolute;
            top: 0;
            left: 46%;
            height: 15rem;
        }

        .container {
            width: 600px;
            max-width: 100%;
        }


        h1, p {
            position: relative;
            text-align: center;
            color: white;
            left: 58%;
        }
        h1{
            margin-top: 30%;
        }
        img {
            max-width: 100%;
            height: auto;
        }

        #fig1 img {
            position: absolute;
            width: 15rem;
            top: 6rem;
            right: 24.9rem;
        }

        #fig2 img {
            position: absolute;
            width: 15rem;
            top: 36.8rem;
            right: 24.9rem;
        }

        #fig3 img {
            position: absolute;
            width: 15rem;
            top: 34.95rem;
            left: 24.9rem;
        }

        #fig4 img {
            position: absolute;
            width: 25rem;
            top: 43.4rem;
            left: 45rem;
        }

        #fig5 img {
            position: absolute;
            width: 25rem;
            top: 10rem;
            left: 30rem;
        }
    </style>
</head>

<body>
    <div class="banner">
        <img src="https://www.dragonrojosoftware.com/images/drs.webp" alt="">
    </div>
    <div class="container">
        <div id="fig1">
            <img src="https://www.dragonrojosoftware.com/images/seccion-6/1.svg" alt="">
        </div>
        <div id="fig2">
            <img src="https://www.dragonrojosoftware.com/images/seccion-6/2.svg" alt="">
        </div>
        <div id="fig3">
            <img src="https://www.dragonrojosoftware.com/images/seccion-6/3.svg" alt="">
        </div>
        <div id="fig4">
            <img src="https://www.dragonrojosoftware.com/images/seccion-6/4.svg" alt="">
        </div>
        <div id="fig5">
            <img src="https://www.dragonrojosoftware.com/images/letras.webp" alt="">
        </div>
        <div class="mensaje">
            <h1>Hola Marcos!</h1>
            <p>Nuevo contacto para <strong>Dragon Rojo Software</strong></p>
            <p>Datos del solicitante:</p>
            <p>Nombre: {{$name}}</p>
            <p>Telefono: {{$phone}}</p>
            <p>Email: {{$email}}</p>
        </div>
    </div>
</body>

</html>