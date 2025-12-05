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
import Performance from "@/pages/Performance";
import Leave from "@/pages/Leave";
import Documents from "@/pages/Documents";
import Compliance from "@/pages/Compliance";

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
      <Route path="/performance" component={Performance} />
      <Route path="/leave" component={Leave} />
      <Route path="/documents" component={Documents} />
      <Route path="/compliance" component={Compliance} />
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