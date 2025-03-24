<!DOCTYPE html>
<html>

<head>

    @include('dashboard.component.head')

</head>

<body>
    <div id="wrapper">

        <div id="page-wrapper" class="gray-bg">
            @include('dashboard.component.nav')
            @include($config['template'])
        </div>
        
    </div>
</body>

</html>