import { Switch, Route } from "wouter";
import { queryClient } from "./lib/queryClient";
import { QueryClientProvider } from "@tanstack/react-query";
import { Toaster } from "@/components/ui/toaster";
import { TooltipProvider } from "@/components/ui/tooltip";
import NotFound from "@/pages/not-found";
import Dashboard from "@/pages/Dashboard";
import Payroll from "@/pages/Payroll";
import TimeManagement from "@/pages/TimeManagement";
import Scheduling from "@/pages/Scheduling";
import Kiosk from "@/pages/Kiosk";
import Login from "@/pages/Login";
import Employees from "@/pages/Employees";
import Incidents from "@/pages/Incidents";

function Router() {
  return (
    <Switch>
      <Route path="/login" component={Login} />
      <Route path="/" component={Dashboard} />
      <Route path="/payroll" component={Payroll} />
      <Route path="/time" component={TimeManagement} />
      <Route path="/scheduling" component={Scheduling} />
      <Route path="/kiosk" component={Kiosk} />
      <Route path="/employees" component={Employees} />
      <Route path="/incidents" component={Incidents} />
      <Route component={NotFound} />
    </Switch>
  );
}

function App() {
  return (
    <QueryClientProvider client={queryClient}>
      <TooltipProvider>
        <Toaster />
        <Router />
      </TooltipProvider>
    </QueryClientProvider>
  );
}

export default App;