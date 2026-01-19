<?php
session_start();
require_once(__DIR__ . '/../includes/config.php');
require_once(__DIR__ . '/../includes/db.php');

if (!isset($_SESSION['admin_id'])) {
    header("Location: index.php");
    exit();
}

// Fetch Contact Messages
$total_messages = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(id) as count FROM contact_messages"))['count'];
$today_messages = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(id) as count FROM contact_messages WHERE DATE(created_at) = CURDATE()"))['count'];

$all_messages = mysqli_query($conn, "SELECT * FROM contact_messages ORDER BY id DESC");

include(__DIR__ . '/layout/sidebar.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Elite Admin | Communication Hub</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    
    <style>
        :root { 
            --accent: #00f2fe; 
            --accent-glow: rgba(0, 242, 254, 0.15); 
            --card-bg: rgba(255, 255, 255, 0.02); 
            --border: rgba(255, 255, 255, 0.08);
        }
        
        body { background: #050505; color: #fff; font-family: 'Plus Jakarta Sans', sans-serif; }
        
        .main-content { margin-left: 280px; padding: 40px; transition: 0.3s; }

        /* Header Style */
        .text-gradient { background: linear-gradient(45deg, #fff, var(--accent)); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }

        /* Stat Cards for Messages */
        .stat-card {
            background: var(--card-bg);
            backdrop-filter: blur(10px);
            border: 1px solid var(--border);
            border-radius: 30px;
            padding: 25px;
            transition: 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }
        .stat-card:hover { border-color: var(--accent); transform: translateY(-5px); }
        .icon-box {
            width: 50px; height: 50px;
            background: rgba(255, 255, 255, 0.05);
            color: var(--accent);
            border-radius: 15px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.2rem; margin-bottom: 15px;
        }

        /* Table Card */
        .table-card { 
            background: var(--card-bg); 
            border-radius: 35px; 
            border: 1px solid var(--border); 
            padding: 35px;
        }

        .table thead th { 
            background: transparent; 
            color: rgba(255,255,255,0.4); 
            border-bottom: 1px solid rgba(255,255,255,0.05);
            padding: 15px; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 1.5px; 
        }

        .table td { padding: 20px 15px; border-bottom: 1px solid rgba(255,255,255,0.02); vertical-align: middle; }
        
        /* Action Buttons */
        .btn-action {
            width: 38px; height: 38px;
            border-radius: 12px;
            display: inline-flex; align-items: center; justify-content: center;
            background: rgba(255, 255, 255, 0.05);
            color: #fff; border: 1px solid var(--border);
            transition: 0.3s;
        }
        .btn-delete:hover { background: rgba(255, 75, 92, 0.2); color: #ff4b5c; border-color: #ff4b5c; }
        
        .message-preview {
            max-width: 250px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            color: rgba(255,255,255,0.6);
        }

        .badge-subject {
            background: rgba(0, 242, 254, 0.1);
            color: var(--accent);
            padding: 5px 12px;
            border-radius: 8px;
            font-size: 0.75rem;
            border: 1px solid rgba(0, 242, 254, 0.2);
        }
    </style>
</head>
<body>

<div class="main-content">
    <div class="d-flex justify-content-between align-items-center mb-5">
        <div class="animate__animated animate__fadeInLeft">
            <h1 class="fw-800 mb-1" style="font-size: 2.5rem;">Message <span class="text-gradient">Hub</span></h1>
            <p class="text-white-50">Manage customer inquiries and feedback.</p>
        </div>
        <div class="d-flex align-items-center gap-4 animate__animated animate__fadeInRight">
            <div class="text-end">
                <h6 class="mb-0 fw-800 text-uppercase"><?php echo $_SESSION['admin_name']; ?></h6>
                <span class="badge bg-dark border border-secondary text-info mt-1">SUPPORT LEAD</span>
            </div>
            <img src="https://ui-avatars.com/api/?name=Admin&background=00f2fe&color=000&bold=true" class="rounded-4 border border-secondary p-1" width="55">
        </div>
    </div>

    <div class="row g-4 mb-5 animate__animated animate__fadeInUp">
        <div class="col-md-4">
            <div class="stat-card">
                <div class="icon-box"><i class="fas fa-inbox"></i></div>
                <p class="text-white-50 small mb-1">Total Inquiries</p>
                <h2 class="fw-800"><?php echo $total_messages; ?></h2>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card">
                <div class="icon-box"><i class="fas fa-calendar-day"></i></div>
                <p class="text-white-50 small mb-1">New Today</p>
                <h2 class="fw-800 text-info"><?php echo $today_messages; ?></h2>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card">
                <div class="icon-box"><i class="fas fa-user-clock"></i></div>
                <p class="text-white-50 small mb-1">Response Status</p>
                <h2 class="fw-800">Active</h2>
            </div>
        </div>
    </div>

    <div class="table-card animate__animated animate__fadeInUp" style="animation-delay: 0.2s;">
        <h4 class="fw-800 mb-4">Inbox</h4>
        <div class="table-responsive">
            <table class="table table-dark">
                <thead>
                    <tr>
                        <th>Sender</th>
                        <th>Subject</th>
                        <th>Message Preview</th>
                        <th>Received On</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = mysqli_fetch_assoc($all_messages)): ?>
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                <img src="https://ui-avatars.com/api/?name=<?php echo $row['name']; ?>&background=random" class="rounded-3 me-3" width="40">
                                <div>
                                    <div class="fw-bold"><?php echo htmlspecialchars($row['name']); ?></div>
                                    <div class="text-white-50 small" style="font-size: 0.7rem;"><?php echo $row['email']; ?></div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge-subject"><?php echo htmlspecialchars($row['subject']); ?></span>
                        </td>
                        <td>
                            <div class="message-preview small"><?php echo htmlspecialchars($row['message']); ?></div>
                        </td>
                        <td>
                            <div class="small fw-bold"><?php echo date('M d, Y', strtotime($row['created_at'])); ?></div>
                            <div class="text-white-50" style="font-size: 0.7rem;"><?php echo date('h:i A', strtotime($row['created_at'])); ?></div>
                        </td>
                        <td class="text-center">
                            <a href="view_message.php?id=<?php echo $row['id']; ?>" class="btn-action me-2" title="View">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="delete_message.php?id=<?php echo $row['id']; ?>" 
                               class="btn-action btn-delete" 
                               onclick="return confirm('Delete this message permanently?');" title="Delete">
                                <i class="fas fa-trash-alt"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

</body>
</html>