<?php
// Shared guard for every admin page: connects to the database (which also
// starts the session) and sends anyone who is not logged in as admin to the
// login page.
include_once __DIR__ . '/../connection/connect.php';

if (empty($_SESSION['adm_id'])) {
    header('Location: index.php');
    exit;
}

// Bootstrap alert box used by the admin forms.
if (!function_exists('admin_alert')) {
    function admin_alert($type, $html)
    {
        return '<div class="alert alert-' . $type . ' alert-dismissible fade show">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            ' . $html . '
        </div>';
    }
}

// Delete button for the admin tables. It is a small POST form with the CSRF
// token (a plain link could be triggered by any other website the admin visits).
if (!function_exists('delete_button')) {
    function delete_button($action, $field, $id, $confirmText, $class = 'btn btn-danger table-action-btn', $iconStyle = '')
    {
        return '<form method="post" action="' . e($action) . '" style="display:inline;margin:0;" onsubmit="return confirm(' . e(json_encode($confirmText)) . ');">'
            . csrf_field()
            . '<input type="hidden" name="' . e($field) . '" value="' . (int) $id . '">'
            . '<button type="submit" class="' . e($class) . '" title="Delete"><i class="fa fa-trash-o"' . ($iconStyle !== '' ? ' style="' . e($iconStyle) . '"' : '') . '></i></button>'
            . '</form>';
    }
}

// <option> tags for a fixed list, with $current pre-selected. A current value
// that is not in the list is kept as an extra option so it is not lost on save.
if (!function_exists('select_options')) {
    function select_options(array $options, $current)
    {
        $html = '';
        $found = false;
        foreach ($options as $option) {
            $selected = strcasecmp($option, (string) $current) === 0;
            $found = $found || $selected;
            $html .= '<option value="' . e($option) . '"' . ($selected ? ' selected' : '') . '>' . e($option) . '</option>';
        }
        if (!$found && (string) $current !== '') {
            $html .= '<option value="' . e($current) . '" selected>' . e($current) . '</option>';
        }
        return $html;
    }
}

$OPEN_HOURS  = array('6am', '7am', '8am', '9am', '10am', '11am', '12pm');
$CLOSE_HOURS = array('3pm', '4pm', '5pm', '6pm', '7pm', '8pm', '9pm', '10pm', '11pm', '12am', '1am', '2am', '3am');
$OPEN_DAYS   = array('Mon-Tue', 'Mon-Wed', 'Mon-Thu', 'Mon-Fri', 'Mon-Sat', '24hr-x7');
