    <?php 

require_once "connect.php";


$conn->query("
    INSERT INTO customers (full_name, phone, email, city, country, document_type)
    VALUES (
        '{$_POST['full_name']}',
        '{$_POST['phone']}',
        '{$_POST['email']}',
        '{$_POST['city']}',
        '{$_POST['country']}',
        '{$_POST['document_type']}'
    )
");

header("Location: ../customer.php");




##########################

// if ($_SERVER["REQUEST_METHOD"] === "POST") {

//     $full_name     = trim($_POST['full_name']);
//     $phone         = trim($_POST['phone']);
//     $email         = trim($_POST['email']);
//     $city          = trim($_POST['city']);
//     $country       = trim($_POST['country']);
//     $document_type = trim($_POST['document_type']);

//     if (
//         empty($full_name) ||
//         empty($phone) ||
//         empty($email) ||
//         empty($city) ||
//         empty($country) ||
//         empty($document_type)
//     ) {
//         die("All fields are required.");
//     }

//     $sql = "INSERT INTO customers 
//             (full_name, phone, email, city, country, document_type)
//             VALUES (?, ?, ?, ?, ?, ?)";

//     $stmt = $conn->prepare($sql);

//     if (!$stmt) {
//         die("Prepare failed: " . $conn->error);
//     }

//     $stmt->bind_param(
//         "ssssss",
//         $full_name,
//         $phone,
//         $email,
//         $city,
//         $country,
//         $document_type
//     );

//     if ($stmt->execute()) {
//         header("Location: ../customer.html?success=1");
//         exit();
//     } else {
//         die("Error inserting customer: " . $stmt->error);
//     }
// } else {
//     // Nëse dikush tenton ta hapë direkt
//     header("Location: ../customer.html");
//     exit();
// }


    ?>