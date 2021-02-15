<!DOCTYPE html>
<html>
    <head>
        <script>
            try {
               window.opener.postMessage('SOCIAL_LOGIN_SUCCESS_{$user_id}_{$created}', '*');                
            } catch (e) {
                console.log(e);
                try {
                    localStorage.setItem('SOCIAL_LOGIN_SUCCESS', '{$user_id}_{$created}');
                    localStorage.setItem('SOCIAL_LOGIN_SUCCESS', '*');
                } catch (e) {
                    console.log(e);
                }
            }
            window.close();
        </script>
    </head>
    <body>
        Авторизация успешна!
    </body>
</html>