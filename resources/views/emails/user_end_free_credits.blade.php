<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Se acabaron tus créditos</title>
</head>
<body>
    <h3>Hola {{ $user->name }},</h3>

    <p>Vemos que has tenido éxito con tu tombofan y las 10 participaciones que tenías de prueba ya las utilizaste. Lamentablemente, a partir de este momento las personas que invites no podrán participar para obtener tus promociones y tampoco podrás obtener sus datos.</p>

    <p>
        Pero no te preocupes, porqué puedes usar tombofans de forma ilimitada y
        participen todas las personas que quieras por un costo mensual de $595 que
        podrás pagar con tarjeta de débito, crédito o efectivo, excepto si eres mamá emprendendora
        que forme parte del <a href="https://www.facebook.com/groups/mundomoms">Club Momy</a>
        y/o grupos de facebook con quienes tenemos convenios.
    </p>

    <p>
        Si eres mamá emprendedora del grupo de facebook llamado Club Momy o con los que tenemos convenio,
        solamente pagarás $99.00 mensuales y tendrás una suscripción ilimitada.
    </p>

    <p>Para pagar solo debes dar clic en alguna de las siguentes ligas y seguir las instrucciones:</p>

    <table cellspacing="0" cellpadding="0" width="100%" border="0">
        <tr>
            <td align="center" height="42" bgcolor="#000091" style="-webkit-border-radius: 7px; -moz-border-radius: 7px; border-radius: 7px; color: #ffffff; display: block;">
                <a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=R4ZA62ASSQJZE" style="font-size:16px; font-weight: bold; font-family: Helvetica, Arial, sans-serif; text-decoration: none; line-height:40px; width:100%; display:inline-block">
                    <span style="color: #FFFFFF">Soy mamá emprendedora de Club Momy y quiero pagar con tarjeta de débito o crédito</span>
                </a>
            </td>
        </tr>
    </table>

    <br>

    <table cellspacing="0" cellpadding="0" width="100%" border="0">
        <tr>
            <td align="center" height="42" bgcolor="#000091" style="-webkit-border-radius: 7px; -moz-border-radius: 7px; border-radius: 7px; color: #ffffff; display: block;">
                <a href="https://compropago.com/comprobante/?id=b2954834-3979-4e5f-90e7-736b112347e1" style="font-size:16px; font-weight: bold; font-family: Helvetica, Arial, sans-serif; text-decoration: none; line-height:40px; width:100%; display:inline-block">
                    <span style="color: #FFFFFF">Soy mamá emprendedora de Club Momy y quiero pagar con efectivo</span>
                </a>
            </td>
        </tr>
    </table>

    <p>
        <b>No formo parte de ningún grupo y quiero comprar la suscripción:</b>
    </p>
    <table cellspacing="0" cellpadding="0" width="100%" border="0">
        <tr>
            <td align="center" height="42" bgcolor="#000091" style="-webkit-border-radius: 7px; -moz-border-radius: 7px; border-radius: 7px; color: #ffffff; display: block;">
                <a href="https://compropago.com/comprobante/?id=e490a587-edec-44fe-81df-09208c309a55" style="font-size:16px; font-weight: bold; font-family: Helvetica, Arial, sans-serif; text-decoration: none; line-height:40px; width:100%; display:inline-block">
                    <span style="color: #FFFFFF">Quiero pagar con efectivo</span>
                </a>
            </td>
        </tr>
    </table>
    <br>
    <table cellspacing="0" cellpadding="0" width="100%" border="0">
        <tr>
            <td align="center" height="42" bgcolor="#000091" style="-webkit-border-radius: 7px; -moz-border-radius: 7px; border-radius: 7px; color: #ffffff; display: block;">
                <a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=6GPWRE6HARWUU" style="font-size:16px; font-weight: bold; font-family: Helvetica, Arial, sans-serif; text-decoration: none; line-height:40px; width:100%; display:inline-block">
                    <span style="color: #FFFFFF">Quiero pagar con tarjeta</span>
                </a>
            </td>
        </tr>
    </table>

    <p>
        <b>No soy mamá del grupo y quiero saber si mi grupo de facebook tiene convenio:</b>
        Envíanos un mail a hola@tombofans.com enviando el link del grupo de facebook al que perteneces.
    </p>

    <p>
        <b>Después de realizar los pagos</b> envía un correo a hola@tombofans.com
        usando el correo que tienes registrado en facebook para habilitarte la suscripción ilimitada.
    </p>
    <p>
        Si tu pago fue con tarjeta todos los meses se renovará automáticamente tu suscripción
        y si fue en efectivo, cada mes deberás de realizar el depósito el mismo día que hiciste el primer pago
        y usando el mismo link, para que tu suscripción no sea cancelada.
    </p>

    <hr>

    <p>Mucho éxito a todo@s</p>
    <p>TomboFans.com</p>
    <p>Si quieres ver más ideas de cómo usar tombofans ve estos <a href="{{ url('/stories') }}">casos de éxito</a>.</p>
</body>
</html>