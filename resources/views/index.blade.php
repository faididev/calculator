<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calculator</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" rel="stylesheet">


</head>

<body class="bg-gray-50 h-screen flex justify-center flex-col items-center">
    <div>
        <h1 class="mb-10 text-center text-4xl text-red-500">Laravel Simple Calculator v1.0</h1>
    </div>
    <div class="w-80 bg-gray-100 rounded-lg shadow-md overflow-hidden">
        <input type="text" class="w-full h-10 bg-white border-none px-4 py-2 text-2xl text-right outline-none" placeholder="0" id="resultScreen" disabled>
        <input type="text" class="w-full h-20 bg-white border-none px-4 py-2 text-3xl text-right outline-none" placeholder="0" id="display" disabled>
        <div class="grid grid-cols-4 gap-1">
            <button class="calc-btn h-20 bg-gray-200 text-3xl transition duration-300 ease-in-out hover:bg-gray-300" onclick="appendToDisplay('7')">7</button>
            <button class="calc-btn h-20 bg-gray-200 text-3xl transition duration-300 ease-in-out hover:bg-gray-300" onclick="appendToDisplay('8')">8</button>
            <button class="calc-btn h-20 bg-gray-200 text-3xl transition duration-300 ease-in-out hover:bg-gray-300" onclick="appendToDisplay('9')">9</button>
            <button class="calc-btn h-20 bg-gray-200 text-3xl transition duration-300 ease-in-out hover:bg-gray-300" onclick="appendToDisplay('/')">/</button>
            <button class="calc-btn h-20 bg-gray-200 text-3xl transition duration-300 ease-in-out hover:bg-gray-300" onclick="appendToDisplay('4')">4</button>
            <button class="calc-btn h-20 bg-gray-200 text-3xl transition duration-300 ease-in-out hover:bg-gray-300" onclick="appendToDisplay('5')">5</button>
            <button class="calc-btn h-20 bg-gray-200 text-3xl transition duration-300 ease-in-out hover:bg-gray-300" onclick="appendToDisplay('6')">6</button>
            <button class="calc-btn h-20 bg-gray-200 text-3xl transition duration-300 ease-in-out hover:bg-gray-300" onclick="appendToDisplay('*')">*</button>
            <button class="calc-btn h-20 bg-gray-200 text-3xl transition duration-300 ease-in-out hover:bg-gray-300" onclick="appendToDisplay('1')">1</button>
            <button class="calc-btn h-20 bg-gray-200 text-3xl transition duration-300 ease-in-out hover:bg-gray-300" onclick="appendToDisplay('2')">2</button>
            <button class="calc-btn h-20 bg-gray-200 text-3xl transition duration-300 ease-in-out hover:bg-gray-300" onclick="appendToDisplay('3')">3</button>
            <button class="calc-btn h-20 bg-gray-200 text-3xl transition duration-300 ease-in-out hover:bg-gray-300" onclick="appendToDisplay('+')">+</button>
            <button class="calc-btn h-20 bg-gray-200 text-3xl transition duration-300 ease-in-out hover:bg-gray-300" onclick="appendToDisplay('0')">0</button>
            <button class="calc-btn h-20 bg-gray-200 text-3xl transition duration-300 ease-in-out hover:bg-gray-300" onclick="appendToDisplay('.')">.</button>
            <button class="calc-btn h-20 bg-gray-200 text-3xl transition duration-300 ease-in-out hover:bg-gray-300" onclick="clearDisplay()">C</button>
            <button class="calc-btn h-20 bg-gray-200 text-3xl transition duration-300 ease-in-out hover:bg-gray-300" onclick="appendToDisplay('-')">-</button>
        </div>
        <button class="calc-btn h-20 btn-equals w-full bg-blue-500 text-white text-3xl transition duration-300 ease-in-out hover:bg-blue-600" onclick="appendToDisplay('=')">=</button>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

    <script>
        const displayScreen = document.getElementById('display');
        const resultScreen  = document.getElementById('resultScreen');
        let result          = 0;
        let lastOerator = "";
        const operatorsReg  = /[+\-*\/=]$/;
        const numbersReg     = /^-?\d*\.?\d*$/;
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
                        resultScreen.value = result;
                        console.log(result, value);
                    }
                } else {
                    resultScreen.value = displayScreen.value + value;
                    result = resultScreen.value;
                    secondInpNumber = displayScreen.value;
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
            const regex = /(-?\d*\.?\d+?)\s*([+\-*\/])\s*(-?\d*\.?\d*)/;

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

        function clearDisplay() {
            displayScreen.value = '';
            resultScreen.value = '';
            result = '';
        }

        function sendInput(expression) {
            let {firstNumber, operator, secondNumber} = splitExpression(expression);

            if (operator && !isNaN(secondNumber)) {
                    processing=true
                    fetch('/store', {
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
                            processing=false;
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'An error occurred. Please try again later.'
                            });
                        });
                }
        }
    </script>

</body>

</html>
