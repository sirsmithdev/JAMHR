import Layout from "@/components/layout/Layout";
import { Card, CardContent, CardHeader, CardTitle, CardDescription } from "@/components/ui/card";
import { Button } from "@/components/ui/button";
import { 
  Users, 
  DollarSign, 
  AlertCircle, 
  CheckCircle2,
  ArrowUpRight,
  Calendar as CalendarIcon
} from "lucide-react";
import { Area, AreaChart, ResponsiveContainer, Tooltip, XAxis, YAxis, CartesianGrid } from "recharts";
import { cn } from "@/lib/utils";

const payrollData = [
  { month: "Jan", amount: 450000 },
  { month: "Feb", amount: 450000 },
  { month: "Mar", amount: 465000 },
  { month: "Apr", amount: 450000 },
  { month: "May", amount: 480000 },
  { month: "Jun", amount: 450000 },
];

const complianceAlerts = [
  { id: 1, title: "NHT Contribution Due", date: "Oct 14, 2025", type: "urgent" },
  { id: 2, title: "Annual Returns Filing", date: "Mar 31, 2026", type: "info" },
];

export default function Dashboard() {
  return (
    <Layout>
      <div className="space-y-8 animate-in fade-in slide-in-from-bottom-4 duration-700">
        
        {/* Header */}
        <div className="flex flex-col md:flex-row md:items-center justify-between gap-4">
          <div>
            <h1 className="text-3xl md:text-4xl text-foreground font-serif">Good Morning, Administrator</h1>
            <p className="text-muted-foreground mt-2">Here's your HR overview for <span className="font-semibold text-primary">October 2025</span>.</p>
          </div>
          <div className="flex gap-3">
            <Button variant="outline">Download Reports</Button>
            <Button className="bg-primary hover:bg-primary/90 text-primary-foreground shadow-lg shadow-primary/20">Run Payroll</Button>
          </div>
        </div>

        {/* Stats Grid */}
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
          <Card className="border-none shadow-sm bg-white hover:shadow-md transition-shadow duration-300">
            <CardHeader className="flex flex-row items-center justify-between space-y-0 pb-2">
              <CardTitle className="text-sm font-medium text-muted-foreground">Total Employees</CardTitle>
              <Users className="h-4 w-4 text-primary" />
            </CardHeader>
            <CardContent>
              <div className="text-2xl font-bold font-serif text-foreground">42</div>
              <p className="text-xs text-emerald-600 flex items-center mt-1">
                <ArrowUpRight className="h-3 w-3 mr-1" /> +2 this month
              </p>
            </CardContent>
          </Card>

          <Card className="border-none shadow-sm bg-white hover:shadow-md transition-shadow duration-300">
            <CardHeader className="flex flex-row items-center justify-between space-y-0 pb-2">
              <CardTitle className="text-sm font-medium text-muted-foreground">Pending Leave</CardTitle>
              <CalendarIcon className="h-4 w-4 text-secondary" />
            </CardHeader>
            <CardContent>
              <div className="text-2xl font-bold font-serif text-foreground">5</div>
              <p className="text-xs text-muted-foreground mt-1">Requests to review</p>
            </CardContent>
          </Card>

          <Card className="border-none shadow-sm bg-white hover:shadow-md transition-shadow duration-300">
            <CardHeader className="flex flex-row items-center justify-between space-y-0 pb-2">
              <CardTitle className="text-sm font-medium text-muted-foreground">Next Payroll</CardTitle>
              <DollarSign className="h-4 w-4 text-emerald-600" />
            </CardHeader>
            <CardContent>
              <div className="text-2xl font-bold font-serif text-foreground">Oct 25</div>
              <p className="text-xs text-muted-foreground mt-1">Est: JMD $1.2M</p>
            </CardContent>
          </Card>

          <Card className="border-none shadow-sm bg-white hover:shadow-md transition-shadow duration-300">
            <CardHeader className="flex flex-row items-center justify-between space-y-0 pb-2">
              <CardTitle className="text-sm font-medium text-muted-foreground">Compliance</CardTitle>
              <CheckCircle2 className="h-4 w-4 text-emerald-500" />
            </CardHeader>
            <CardContent>
              <div className="text-2xl font-bold font-serif text-foreground">98%</div>
              <p className="text-xs text-emerald-600 mt-1">All taxes filed</p>
            </CardContent>
          </Card>
        </div>

        <div className="grid grid-cols-1 lg:grid-cols-3 gap-8">
          
          {/* Payroll Chart */}
          <Card className="lg:col-span-2 border-none shadow-md bg-white">
            <CardHeader>
              <CardTitle className="font-serif">Payroll History (JMD)</CardTitle>
              <CardDescription>Gross payroll expenditure for the last 6 months</CardDescription>
            </CardHeader>
            <CardContent className="h-[300px]">
              <ResponsiveContainer width="100%" height="100%">
                <AreaChart data={payrollData}>
                  <defs>
                    <linearGradient id="colorPayroll" x1="0" y1="0" x2="0" y2="1">
                      <stop offset="5%" stopColor="hsl(216 90% 35%)" stopOpacity={0.1}/>
                      <stop offset="95%" stopColor="hsl(216 90% 35%)" stopOpacity={0}/>
                    </linearGradient>
                  </defs>
                  <CartesianGrid strokeDasharray="3 3" vertical={false} stroke="hsl(var(--border))" />
                  <XAxis 
                    dataKey="month" 
                    axisLine={false} 
                    tickLine={false} 
                    tick={{fill: 'hsl(var(--muted-foreground))', fontSize: 12}} 
                    dy={10}
                  />
                  <YAxis 
                    axisLine={false} 
                    tickLine={false} 
                    tick={{fill: 'hsl(var(--muted-foreground))', fontSize: 12}} 
                    tickFormatter={(value) => `$${value/1000}k`}
                  />
                  <Tooltip 
                    contentStyle={{
                      backgroundColor: 'white',
                      border: '1px solid hsl(var(--border))',
                      borderRadius: '8px',
                      boxShadow: '0 4px 6px -1px rgb(0 0 0 / 0.1)'
                    }}
                    formatter={(value) => [`$${value.toLocaleString()}`, 'Amount']}
                  />
                  <Area 
                    type="monotone" 
                    dataKey="amount" 
                    stroke="hsl(216 90% 35%)" 
                    strokeWidth={3}
                    fillOpacity={1} 
                    fill="url(#colorPayroll)" 
                  />
                </AreaChart>
              </ResponsiveContainer>
            </CardContent>
          </Card>

          {/* Compliance / Quick Actions */}
          <div className="space-y-6">
            <Card className="border-none shadow-md bg-white">
              <CardHeader>
                <CardTitle className="font-serif text-lg">Upcoming Deadlines</CardTitle>
              </CardHeader>
              <CardContent className="space-y-4">
                {complianceAlerts.map((alert) => (
                  <div key={alert.id} className="flex items-start gap-3 p-3 rounded-lg bg-muted/30 border border-muted">
                    <AlertCircle className={cn(
                      "h-5 w-5 mt-0.5", 
                      alert.type === 'urgent' ? "text-destructive" : "text-primary"
                    )} />
                    <div>
                      <p className="font-medium text-sm">{alert.title}</p>
                      <p className="text-xs text-muted-foreground">Due: {alert.date}</p>
                    </div>
                  </div>
                ))}
                <Button variant="ghost" className="w-full text-primary text-sm h-auto py-2">
                  View Compliance Calendar
                </Button>
              </CardContent>
            </Card>

            <Card className="border-none shadow-md bg-gradient-to-br from-primary to-sidebar text-primary-foreground">
              <CardHeader>
                <CardTitle className="font-serif text-white">Quick Tax Calc</CardTitle>
                <CardDescription className="text-primary-foreground/80">Estimate deductions for new hires</CardDescription>
              </CardHeader>
              <CardContent>
                <Button variant="secondary" className="w-full bg-secondary text-secondary-foreground hover:bg-secondary/90">
                  Open Calculator
                </Button>
              </CardContent>
            </Card>
          </div>
        </div>
      </div>
    </Layout>
  );
}