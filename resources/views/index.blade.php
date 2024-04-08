<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calculator</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        .calc-btn {
            transition: background-color 0.3s ease;
        }

        .calc-btn:hover {
            background-color: #f1f1f1;
        }

        .calc-container {
            width: 320px;
            background-color: #f1f1f1;
            border-radius: 8px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .display, .result {
            width: 100%;
            height: 80px;
            background-color: #fafafa;
            border: none;
            padding: 0 16px;
            font-size: 36px;
            text-align: right;
            outline: none;
        }

        .result {
            height: 40px;
            font-size: 16px;
        }

        .btn-container {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1px;
        }

        .btn {
            background-color: #e0e0e0;
            border: none;
            padding: 16px;
            font-size: 24px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .btn:hover {
            background-color: #f1f1f1;
        }
    </style>
</head>

<body class="bg-gray-200 h-screen flex justify-center items-center">
    <div class="calc-container">
        <input type="text" class="result" placeholder="0" id="resultScreen" disabled>
        <input type="text" class="display" placeholder="0" id="display" disabled>
        <div class="btn-container">
            <button class="btn" onclick="appendToDisplay('7')">7</button>
            <button class="btn" onclick="appendToDisplay('8')">8</button>
            <button class="btn" onclick="appendToDisplay('9')">9</button>
            <button class="btn" onclick="appendToDisplay('/')">/</button>
            <button class="btn" onclick="appendToDisplay('4')">4</button>
            <button class="btn" onclick="appendToDisplay('5')">5</button>
            <button class="btn" onclick="appendToDisplay('6')">6</button>
            <button class="btn" onclick="appendToDisplay('*')">*</button>
            <button class="btn" onclick="appendToDisplay('1')">1</button>
            <button class="btn" onclick="appendToDisplay('2')">2</button>
            <button class="btn" onclick="appendToDisplay('3')">3</button>
            <button class="btn" onclick="appendToDisplay('+')">+</button>
            <button class="btn" onclick="appendToDisplay('0')">0</button>
            <button class="btn" onclick="appendToDisplay('.')">.</button>
            <button class="btn" onclick="clearDisplay()">C</button>
            <button class="btn" onclick="appendToDisplay('-')">-</button>
        </div>
        <button class="btn w-full" onclick="appendToDisplay('=')">=</button>
    </div>

    <script>
        const displayScreen = document.getElementById('display');
        const resultScreen  = document.getElementById('resultScreen');
        let result          = 0;
        let lastOerator = "";
        const operatorsReg  = /[+\-*\/=]$/
        const numbersReg     = /^-?\d*\.?\d+$/
        const endWithOperator = /^\d+.*[+\-*\/=]$/;

        let processing = false;

        async function appendToDisplay(value) {

            if (processing) return;

            if(isNumber(value)) {
                if(hasOperator(result) && secondInpNumber) {
                    displayScreen.value = value;
                    secondInpNumber = ""
                } else {
                    displayScreen.value += value;
                }
            }

            if(isOperator(value)) {
                if (hasOperator(result)) {
                    if(displayScreen.value && !secondInpNumber) {
                        result += displayScreen.value
                        await sendInput(result)
                    } else {
                        result = result.replace(operatorsReg, value);
                        resultScreen.value = result
                        console.log(result, value)
                    }
                } else {
                    resultScreen.value = displayScreen.value + value
                    result = resultScreen.value
                    secondInpNumber = displayScreen.value
                }
                lastOerator = value
            }
        }

        function hasOperator(value) {
            return endWithOperator.test(value)
        }

        function isOperator(value) {
            return operatorsReg.test(value)
        }

        function isNumber(value) {
            return numbersReg.test(value)
        }

        function splitExpression(expression) {
            // Regular expression to match numbers and operators
            const regex = /(\d+)([+\-*\/])(\d+)/;
            const matches = expression.match(regex);

            if (matches) {
                let firstNumber = matches[1];
                let operator = matches[2];
                let secondNumber = matches[3];

                return { firstNumber, operator, secondNumber };
            } else {
                return null; // Return null if the expression doesn't match the pattern
            }
        }

        function sendInput(expression) {
            let {firstNumber, operator, secondNumber} = splitExpression(expression);

            if (operator && !isNaN(secondNumber)) {
                    processing=true
                    fetch('/calculate', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                firstNumber: firstNumber,
                                operator: operator,
                                secondNumber: secondNumber
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            resultScreen.value = data.result + lastOerator;
                            result = resultScreen.value
                            displayScreen.value = data.result
                            secondInpNumber = data.result
                            processing=false
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            processing=false
                        });
                }
        }
    </script>

</body>

</html>

