<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP Backend Developer Interview Task</title>
    <!-- Load Vite development or production build assets -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f4f4f9;
            color: #333;
            height: 100vh;
        }

        #root {
            display: flex;
            flex-direction: column;
            height: 100vh;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px;
            background-color: #ffffff;
            width: 100%;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .top-left-container {
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .count-row {
            font-size: 1rem;
            margin-bottom: 5px;
            color: #757575;
        }

        .top-right-container {
            display: flex;
            align-items: center;
            height: 100%;
        }

        .refresh-button {
            background-color: #6200ea;
            color: #ffffff;
            border: none;
            border-radius: 4px;
            height: 100%;
            padding: 0 20px;
            font-size: 1rem;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .refresh-button:hover {
            background-color: #3700b3;
        }

        .filter-box {
            width: 100%;
            padding: 10px 20px;
            background-color: #ffffff;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: center;
        }

        .filter-box div {
            display: flex;
            width: 100%;
            gap: 10px;
        }

        select, 
        input[type="text"] {
            height: 40px;
            font-size: 16px;
            padding: 0 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            outline: none;
            width: 100%;
        }

        select:focus, 
        input[type="text"]:focus {
            border-color: #6200ea;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            flex-grow: 1;
        }

        table th, 
        table td {
            padding: 15px;
            text-align: left;
        }

        table th {
            background-color: #6200ea;
            color: #ffffff;
            font-weight: 500;
        }

        table tbody tr:nth-child(odd) {
            background-color: #f9f9f9;
        }

        table tbody tr:hover {
            background-color: #f1f1f1;
        }

        .paginator-container {
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
            background-color: #ffffff;
        }

        .paginator-button {
            background-color: #6200ea;
            color: #ffffff;
            border: none;
            border-radius: 4px;
            padding: 10px 20px;
            font-size: 1rem;
            cursor: pointer;
            transition: background-color 0.3s;
            margin: 0 10px;
        }

        .paginator-button:disabled {
            background-color: #ccc;
            cursor: not-allowed;
        }

        .paginator-button:hover:not(:disabled) {
            background-color: #3700b3;
        }

        .page-numbers {
            display: flex;
            gap: 5px;
        }

        .page-number {
            background-color: #ffffff;
            border: 1px solid #6200ea;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            display: flex;
            justify-content: center;
            align-items: center;
            font-weight: 500;
            cursor: pointer;
            transition: background-color 0.3s, color 0.3s;
        }

        .page-number.active {
            background-color: #6200ea;
            color: #ffffff;
        }

        .page-number:hover:not(.active) {
            background-color: #e3f2fd;
        }
    </style>


</head>

<body>
    <div id="root"></div>
    <script type="module">
        import RefreshRuntime from 'http://localhost:5173/@react-refresh'
        RefreshRuntime.injectIntoGlobalHook(window)
        window.$RefreshReg$ = () => {}
        window.$RefreshSig$ = () => (type) => type
        window.__vite_plugin_react_preamble_installed__ = true
    </script>
    <script type="module" src="http://localhost:5173/@vite/client"></script>
    <script type="module" src="http://localhost:5173/src/main.jsx"></script>
</body>

</html>