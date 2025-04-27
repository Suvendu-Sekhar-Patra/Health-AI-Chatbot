<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}




///////
// Clear session data on logout


// Check if user is logged in
if (!isset($_SESSION['full_name'])) {
    unset($_SESSION['conversation']);
}

// Initialize conversation if user is logged in
if (isset($_SESSION['full_name']) && !isset($_SESSION['conversation'])) {
    $_SESSION['conversation'] = [];
}
////////




if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST['user_input'])) {
        if (!isset($_SESSION['full_name'])) {
            echo json_encode(['error' => 'Please login first']);
            exit;
        }
    
    $userInput = $_POST['user_input'];

    if (!isset($_SESSION['conversation'])) {
        $_SESSION['conversation'] = [];
    }

    // Store user input in session
    $_SESSION['conversation'][] = "User: " . $userInput;

    $apiKey = "gemini api key"; 
    $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key=$apiKey";
    
    // Structure the conversation
    $conversationHistory = implode("\n", $_SESSION['conversation']);

    $data = json_encode([
        "contents" => [
            [
                "parts" => [
                    [
                        "text" => "You are a professional healthcare chatbot. Engage in a structured conversation step by step, keeping previous messages for context. Ask one question at a time:
                        \n1. Greet the user and acknowledge their concern. Ask about their symptoms in detail.
                        \n2. Ask how long they have been experiencing the symptoms.
                        \n3. Ask them to rate the severity of their symptoms on a scale of 1-10.
                        \n4. Ask about any chronic conditions.
                        \n5. Ask if they are taking any medications.
                        \n6. Ask about their diet.
                        \n7. Ask about their exercise habits.
                        \n8. Summarize their responses in a concise health summary .
                        \n9. Provide personalized recommendations, including home remedies and over-the-counter treatments. If symptoms indicate a serious condition, suggest consulting a doctor.
                        \n10. Offer to generate a health report or diagnosis summary if needed.
                        \nGuidelines:
                        \n- Ask one question at a time and wait for a response before proceeding.
                        \n- If the user gives unclear answers, provide examples to guide them.
                        \n- Keep responses professional,short,minimal, to the point, concise, and empathetic.
                        \n- Always encourage seeking medical advice if symptoms are severe or prolonged.
                        \nCurrent conversation history:\n$conversationHistory\n\nUser's latest input: '$userInput'"
                    ]
                ]
            ]
        ]
    ]);

    $options = [
        "http" => [
            "header" => "Content-Type: application/json\r\n",
            "method" => "POST",
            "content" => $data,
            "ignore_errors" => true
        ]
    ];

    $context = stream_context_create($options);
    $result = file_get_contents($url, false, $context);
    
    if ($result === FALSE) {
        $error = error_get_last();
        echo json_encode(['error' => 'API request failed: ' . $error['message']]);
        exit;
    }

    file_put_contents('api_response.log', $result); 
    $responseData = json_decode($result, true);
    
    if (isset($responseData['candidates'][0]['content']['parts'][0]['text'])) {
        $botResponse = $responseData['candidates'][0]['content']['parts'][0]['text'];
        $_SESSION['conversation'][] = $botResponse; // Store bot response
        echo json_encode(['response' => $botResponse]);
    } else {
        echo json_encode(['error' => 'Unexpected response structure']);
    }
}
}




$name = '';
$email = '';
$phone = '';
$password = '';
$password1 = '';
$password2 = '';
$errors = array();

// Connect to the Database
$db = mysqli_connect('localhost', 'root', '', 'healthai');

if (!$db) {
    die("Database connection failed: " . mysqli_connect_error());
}

// If the create account button is clicked
if (isset($_POST['createAccount'])) {
    $name = mysqli_real_escape_string($db, $_POST['name']);
    $email = mysqli_real_escape_string($db, $_POST['email1']);
    $phone = mysqli_real_escape_string($db, $_POST['phone']);
    $password1 = mysqli_real_escape_string($db, $_POST['pwd1']);
    $password2 = mysqli_real_escape_string($db, $_POST['pwd2']);

    // Ensuring that form fields are filled properly
    if (empty($name)) array_push($errors, "Name is required.");
    if (empty($email)) array_push($errors, "Email is required.");
    if (empty($phone)) array_push($errors, "Mobile No. is required.");
    if (empty($password1)) array_push($errors, "Password is required.");
    if ($password1 != $password2) array_push($errors, "The two passwords do not match.");

    $emailQuery = mysqli_query($db, "SELECT * FROM user WHERE email_id='$email'");

    if (mysqli_num_rows($emailQuery) > 0) {
        array_push($errors, "Email already exists.");
    }

    // If there are no errors, save user to Database
    if (count($errors) == 0) {
        $sql = "INSERT INTO user (full_name, email_id, mobile_no, password) VALUES ('$name', '$email', '$phone', '$password1')";
        mysqli_query($db, $sql);
        $_SESSION['full_name'] = $name;
        $_SESSION['success'] = "You are now logged in";
        header('location: chat.php');
        exit();
    }
}

// User Login
if (isset($_POST['login'])) {
    // ob_clean();
    // header('Content-Type: application/json');
    
    // $errors = array();

    $email = mysqli_real_escape_string($db, $_POST['email1']);
    $password = mysqli_real_escape_string($db, $_POST['pwd1']);

    if (empty($email)) array_push($errors, "Email is required.");
    if (empty($password)) array_push($errors, "Password is required.");

    if (count($errors) == 0) {
        $query = "SELECT * FROM user WHERE email_id='$email' AND password='$password'";
        $result = mysqli_query($db, $query);

        if ($result && mysqli_num_rows($result) == 1) {
            $user = mysqli_fetch_assoc($result);
            $_SESSION['full_name'] = $user['full_name'];
            $_SESSION['email_id'] = $user['email_id'];
            $_SESSION['mobile_no'] = $user['mobile_no'];
            $_SESSION['password'] = $user['password'];
            $_SESSION['success'] = "You are now logged in";
            header('location: chat.php');
            exit();
        } else {
            array_push($errors, "Username or Password incorrect");
        }
    }
}

// Logout
if (isset($_GET['logout'])) {
    session_destroy();
    unset($_SESSION['full_name']);
    header('location: index.php');
    exit();
}

// User Update
// if (isset($_POST['update'])) {
//     $response = array();
//     $data = array();
//     $data[0] = mysqli_real_escape_string($db, $_POST['name']);
//     $data[1] = mysqli_real_escape_string($db, $_POST['email1']);
//     $data[2] = mysqli_real_escape_string($db, $_POST['phone']);
//     $data[3] = mysqli_real_escape_string($db, $_POST['pwd1']);
//     $data[4] = mysqli_real_escape_string($db, $_POST['pwd2']);

//     if (empty($data[0])) array_push($errors, "Name is required.");
//     if (empty($data[1])) array_push($errors, "Email is required.");
//     if (empty($data[2])) array_push($errors, "Mobile No. is required.");
//     if (empty($data[3])) array_push($errors, "Password is required.");
//     if ($data[3] != $data[4]) array_push($errors, "The two passwords do not match.");

//     if (count($errors) == 0) {
//         $update_Query = "UPDATE user SET full_name='$data[0]', email_id='$data[1]', mobile_no='$data[2]', password='$data[3]' WHERE email_id='$data[1]'";
//         mysqli_query($db, $update_Query);
//     }
// }



if (isset($_POST['update'])) {
    // Prevent any output before headers
    ob_clean();
    header('Content-Type: application/json');
    
    $errors = array();
    
    $name = mysqli_real_escape_string($db, $_POST['name']);
    $email = mysqli_real_escape_string($db, $_POST['email1']);
    $phone = mysqli_real_escape_string($db, $_POST['phone']);
    $password1 = mysqli_real_escape_string($db, $_POST['pwd1']);
    $password2 = mysqli_real_escape_string($db, $_POST['pwd2']);

    // Validation
    if (empty($name)) array_push($errors, "Name is required.");
    if (empty($email)) array_push($errors, "Email is required.");
    if (empty($phone)) array_push($errors, "Mobile No. is required.");
    if (empty($password1)) array_push($errors, "Password is required.");
    if ($password1 != $password2) array_push($errors, "The two passwords do not match.");

    if (count($errors) == 0) {
        try {
            $update_Query = "UPDATE user SET full_name=?, mobile_no=?, password=? WHERE email_id=?";
            $stmt = mysqli_prepare($db, $update_Query);
            
            if ($stmt) {
                mysqli_stmt_bind_param($stmt, "ssss", $name, $phone, $password1, $email);
                
                if (mysqli_stmt_execute($stmt)) {
                    $_SESSION['full_name'] = $name;
                    $_SESSION['mobile_no'] = $phone;
                    $_SESSION['password'] = $password1;
                    echo json_encode(['success' => true]);
                } else {
                    echo json_encode(['success' => false, 'error' => 'Database update failed: ' . mysqli_error($db)]);
                }
                mysqli_stmt_close($stmt);
            } else {
                echo json_encode(['success' => false, 'error' => 'Prepared statement failed: ' . mysqli_error($db)]);
            }
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'error' => 'Server error: ' . $e->getMessage()]);
        }
    } else {
        echo json_encode(['success' => false, 'error' => implode(", ", $errors)]);
    }
    exit;
}

?>
