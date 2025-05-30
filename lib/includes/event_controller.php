<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../models/Auth.php';
require_once __DIR__ . '/../models/Logs.php';
require_once __DIR__ . '/../models/CourtEvent.php';
require_once __DIR__ . '/../includes/helpers.php';

if (!Auth::isAuthenticated()) {
    header("Location: " . BASE_URL . "/login");
    exit;
}

// internal routing within controller for CRUD operations
switch ($action) {
    case 'add':
        save_event($app);
        break;
    case 'edit':
        save_event($app, $id);
        break;
    case 'delete':
        delete_event($app, $id);
        break;
    default:
        ($app->render)('standard', '404');
        exit;
}

// Combined add/edit logic
function save_event($app, $eventID = null) {
    try {
        $caseID = $_GET['caseID'] ?? null;
        if (!$caseID) {
            throw new Exception("Case ID required.");
        }

        $isEdit = isset($eventID);
        $event = $isEdit ? CourtEvent::getEventByEventID($eventID) : null;

        if ($isEdit && !$event) {
            throw new Exception("Event not found.");
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $location = trim($_POST['location'] ?? '');
            $description = trim($_POST['description'] ?? '');
            $date = trim($_POST['date'] ?? '');

            if (empty($location) || empty($description) || empty($date)) {
                throw new Exception("All fields are required.");
            }

            $data = [
                'location' => $location,
                'description' => $description,
                'date' => $date
            ];

            $userID = $_SESSION['user_id'] ?? null;
            $username = $_SESSION['username'] ?? 'Unknown';

            if ($isEdit) {
                $old = $event;
                CourtEvent::update($eventID, $data);

                $changes = [];
                foreach (['location', 'description', 'date'] as $key) {
                    $oldVal = $old[ucfirst($key)] ?? '';
                    $newVal = $data[$key];
                    if ($oldVal !== $newVal) {
                        $changes[] = ucfirst($key) . " changed from '$oldVal' to '$newVal'";
                    }
                }

                $changeSummary = $changes ? implode("; ", $changes) : "No changes were made.";

                $logMessage = "User $username (ID: $userID) updated event #$eventID for case #$caseID.\n$changeSummary";
                $successMessage = "Event updated successfully.";
            } else {
                CourtEvent::create($caseID, $data);

                $logMessage = sprintf(
                    "User %s (ID: %s) created new event for case #%d.\nLocation: '%s'; Description: '%s'; Date: '%s'",
                    $username,
                    $userID,
                    $caseID,
                    $data['location'],
                    $data['description'],
                    $data['date']
                );

                $successMessage = "Event added successfully.";
            }

            LogModel::log_action($userID, $logMessage);
            redirect_with_success("/case/edit/" . $caseID, $successMessage);
        }

        // Render form (GET)
        ($app->render)('standard', 'forms/event_form', [
            'caseID' => $caseID,
            'event' => $event,
            'isEdit' => $isEdit,
        ]);

    } catch (Exception $e) {
        render_error($app, $e->getMessage());
    }
}

function delete_event($app, $eventID) {
    try {
        $caseID = $_GET['caseID'] ?? null;
        if (!$caseID) {
            throw new Exception("Case ID required.");
        }

        $event = CourtEvent::getEventByEventID($eventID);
        if (!$event) {
            throw new Exception("Event not found.");
        }

        $location = $event['location'] ?? $event['Location'] ?? '[unknown]';
        $description = $event['description'] ?? $event['Description'] ?? '[unknown]';
        $date = $event['date'] ?? $event['Date'] ?? '[unknown]';

        CourtEvent::delete($eventID);

        $userID = $_SESSION['user_id'] ?? null;
        $username = $_SESSION['username'] ?? 'Unknown';

        $logMessage = sprintf(
            "User %s (ID: %s) deleted event #%d from case #%d.\nLocation: '%s'; Description: '%s'; Date: '%s'",
            $username,
            $userID,
            $eventID,
            $caseID,
            $location,
            $description,
            $date
        );

        LogModel::log_action($userID, $logMessage);

        redirect_with_success("/case/edit/" . $caseID, "Event deleted successfully.");

    } catch (Exception $e) {
        render_error($app, $e->getMessage());
    }
}
