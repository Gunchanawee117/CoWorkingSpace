<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
</head>
<body>
    
</body>
</html>

<?php
// Include your database connection
include('db_connection.php');

if (isset($_GET['report_id'])) {
    $reportId = $_GET['report_id'];

    // Check if the report exists and is unresolved
    $sql = "SELECT issue_status FROM reports WHERE report_id = $reportId";
    $result = $connection->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $issueStatus = $row["issue_status"];

        if ($issueStatus == 1) {
            // Update the issue status to resolved (or any other desired action)
            $updateSql = "UPDATE reports SET issue_status = 0 WHERE report_id = $reportId";
            if ($connection->query($updateSql) === TRUE) {
                // Display a success message using SweetAlert
                echo "<script>
                        Swal.fire({
                            title: 'Success!',
                            text: 'The report has been approved.',
                            icon: 'success',
                            confirmButtonText: 'OK'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = 'admin_allreport.php'; // Redirect to the reports list page
                            }
                        });
                    </script>";
            } else {
                // Display an error message using SweetAlert if the update fails
                echo "<script>
                        Swal.fire({
                            title: 'Error!',
                            text: 'There was an error approving the report.',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    </script>";
            }
        } else {
            // Display a message using SweetAlert if the report is already resolved
            echo "<script>
                    Swal.fire({
                        title: 'Warning!',
                        text: 'This report has already been resolved.',
                        icon: 'warning',
                        confirmButtonText: 'OK'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = 'admin_allreport.php'; // Redirect to the reports list page
                        }
                    });
                </script>";
        }
    } else {
        // Display an error message using SweetAlert if the report doesn't exist
        echo "<script>
                Swal.fire({
                    title: 'Error!',
                    text: 'No report found with the provided ID.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = 'admin_allreport.php'; // Redirect to the reports list page
                    }
                });
            </script>";
    }
} else {
    // Display an error message using SweetAlert if no report ID is provided
    echo "<script>
            Swal.fire({
                title: 'Error!',
                text: 'No report ID provided.',
                icon: 'error',
                confirmButtonText: 'OK'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'admin_allreport.php'; // Redirect to the reports list page
                }
            });
        </script>";
}

// Close the database connection
$connection->close();
?>
