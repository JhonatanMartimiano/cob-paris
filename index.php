<?php
ob_start();

require __DIR__ . "/vendor/autoload.php";

/**
 * BOOTSTRAP
 */

use CoffeeCode\Router\Router;
use Source\Core\Session;

$session = new Session();
$route = new Router(url(), ":");
$route->namespace("Source\App");

/**
 * WEB ROUTES
 */
$route->group(null);
$route->get("/", "Web:home");


/**
 * ADMIN ROUTES
 */
$route->namespace("Source\App\Admin");
$route->group("/admin");

//login
$route->get("/", "Login:root");
$route->get("/login", "Login:login");
$route->post("/login", "Login:login");
$route->get("/register", "Login:register");
$route->post("/register", "Login:register");
$route->get("/forget", "Web:forget");
$route->post("/forget", "Web:forget");
$route->get("/forget/{code}", "Web:reset");
$route->post("/forget/reset", "Web:reset");

//dash
$route->get("/dash", "Dash:dash");
$route->get("/dash/home", "Dash:home");
$route->post("/dash/home", "Dash:home");
$route->post("/dash/ticket-search", "Dash:ticketSearch");
$route->get("/logoff", "Dash:logoff");

//users
$route->get("/users/home", "Users:home");
$route->post("/users/home", "Users:home");
$route->get("/users/home/{search}/{page}", "Users:home");
$route->get("/users/user", "Users:user");
$route->post("/users/user", "Users:user");
$route->get("/users/user/{user_id}", "Users:user");
$route->post("/users/user/{user_id}", "Users:user");

//clients
$route->get("/clients/home", "Clients:home");
$route->post("/clients/home", "Clients:home");
$route->get("/clients/home/{search}/{page}", "Clients:home");
$route->get("/clients/client", "Clients:client");
$route->post("/clients/client", "Clients:client");
$route->get("/clients/client/{client_id}", "Clients:client");
$route->post("/clients/client/{client_id}", "Clients:client");

//tickets
$route->get("/tickets/home", "Tickets:home");
$route->post("/tickets/home", "Tickets:home");
$route->get("/tickets/home/{search}/{page}", "Tickets:home");
$route->get("/tickets/ticket", "Tickets:ticket");
$route->post("/tickets/ticket", "Tickets:ticket");
$route->get("/tickets/ticket/{ticket_id}", "Tickets:ticket");
$route->post("/tickets/ticket/{ticket_id}", "Tickets:ticket");
$route->post("/tickets/search-client/{cpf_cnpj}", "Tickets:searchClient");
$route->post("/tickets/others-tickets", "Tickets:othersTickets");

//charges
$route->get("/charges/home", "Charges:home");
$route->post("/charges/home", "Charges:home");
$route->get("/charges/home/{search}/{page}", "Charges:home");
$route->get("/charges/charge", "Charges:charge");
$route->post("/charges/charge", "Charges:charge");
$route->get("/charges/charge/{ticket_id}", "Charges:charge");
$route->post("/charges/charge/{ticket_id}", "Charges:charge");
$route->get("/charges/filter/{filter}", "Charges:filter");
$route->get("/charges/filter/{filter}/{search}/{page}", "Charges:filter");
$route->post("/charges/filter/{filter}", "Charges:filter");
$route->post("/charges/filter", "Charges:filter");
$route->post("/charges/report", "Charges:report");

//finesheds
$route->get("/finisheds/home", "Finisheds:home");
$route->post("/finisheds/home", "Finisheds:home");
$route->get("/finisheds/home/{search}/{page}", "Finisheds:home");
$route->get("/finisheds/finished", "Finisheds:finished");
$route->post("/finisheds/finished", "Finisheds:finished");
$route->get("/finisheds/finished/{ticket_id}", "Finisheds:finished");
$route->post("/finisheds/finished/{ticket_id}", "Finisheds:finished");

//agreements
$route->get("/agreements/home", "Agreements:home");
$route->post("/agreements/home", "Agreements:home");
$route->get("/agreements/home/{search}/{page}", "Agreements:home");
$route->get("/agreements/agreement", "Agreements:agreement");
$route->post("/agreements/agreement", "Agreements:agreement");
$route->get("/agreements/agreement/{agreement_id}", "Agreements:agreement");
$route->post("/agreements/agreement/{agreement_id}", "Agreements:agreement");
$route->post("/agreements/search-client/{cpf_cnpj}", "Agreements:searchClient");

//agreeds
$route->get("/agreeds/home", "Agreeds:home");
$route->post("/agreeds/home", "Agreeds:home");
$route->get("/agreeds/home/{search}/{page}", "Agreeds:home");
$route->post("/agreeds/remove-agreed/{agreed_id}", "Agreeds:removeAgreed");

//reports
$route->get("/reports/home", "Reports:home");
$route->post("/reports/home", "Reports:home");
$route->get("/reports/home/{search}/{page}", "Reports:home");
$route->get("/reports/report", "Reports:report");
$route->post("/reports/report", "Reports:report");
$route->get("/reports/report/{client_id}", "Reports:report");
$route->post("/reports/report/{client_id}", "Reports:report");

//notification center
$route->post("/notifications/count", "Notifications:count");
$route->post("/notifications/list", "Notifications:list");

/**
 * ERROR ROUTES
 */
$route->group("/ops");
$route->get("/{errcode}", "Web:error");

/**
 * ROUTE
 */
$route->dispatch();

/**
 * ERROR REDIRECT
 */
if ($route->error()) {
    $route->redirect("/ops/{$route->error()}");
}

ob_end_flush();
