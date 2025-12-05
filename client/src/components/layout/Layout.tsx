import { useState } from "react";
import { Link, useLocation } from "wouter";
import { 
  LayoutDashboard, 
  Users, 
  FileText, 
  Calendar, 
  Settings, 
  LogOut, 
  Menu,
  Calculator,
  Briefcase,
  Clock,
  CalendarDays,
  MonitorSmartphone,
  AlertTriangle
} from "lucide-react";
import { Button } from "@/components/ui/button";
import { Sheet, SheetContent, SheetTrigger } from "@/components/ui/sheet";
import { cn } from "@/lib/utils";

interface NavItemProps {
  href: string;
  icon: React.ElementType;
  label: string;
  active?: boolean;
}

const NavItem = ({ href, icon: Icon, label, active }: NavItemProps) => (
  <Link href={href}>
    <div
      className={cn(
        "flex items-center gap-3 px-3 py-2.5 rounded-md transition-all duration-200 group cursor-pointer",
        active 
          ? "bg-sidebar-primary text-sidebar-primary-foreground shadow-sm font-medium" 
          : "text-sidebar-foreground/80 hover:bg-sidebar-accent hover:text-sidebar-accent-foreground"
      )}
    >
      <Icon className={cn("h-5 w-5", active ? "text-sidebar-primary-foreground" : "text-sidebar-foreground/60 group-hover:text-sidebar-accent-foreground")} />
      <span>{label}</span>
    </div>
  </Link>
);

export default function Layout({ children }: { children: React.ReactNode }) {
  const [location] = useLocation();
  const [isMobileOpen, setIsMobileOpen] = useState(false);

  const navItems = [
    { href: "/", icon: LayoutDashboard, label: "Dashboard" },
    { href: "/employees", icon: Users, label: "Employees" },
    { href: "/payroll", icon: Calculator, label: "Payroll & Tax" },
    { href: "/time", icon: Clock, label: "Time & Attendance" },
    { href: "/scheduling", icon: CalendarDays, label: "Scheduling" },
    { href: "/leave", icon: Calendar, label: "Leave Management" },
    { href: "/incidents", icon: AlertTriangle, label: "Incident Reporting" },
    { href: "/documents", icon: FileText, label: "Documents" },
    { href: "/compliance", icon: Briefcase, label: "Labor Laws" },
  ];

  const SidebarContent = () => (
    <div className="flex flex-col h-full">
      <div className="p-6 border-b border-sidebar-border/30">
        <div className="flex items-center gap-2">
          <div className="h-8 w-8 rounded-lg bg-secondary flex items-center justify-center">
            <span className="font-serif font-bold text-secondary-foreground text-lg">J</span>
          </div>
          <span className="font-serif font-bold text-xl text-sidebar-foreground tracking-tight">JamHR</span>
        </div>
        <p className="text-xs text-sidebar-foreground/50 mt-1 pl-10">Compliant Jamaican HR</p>
      </div>

      <div className="flex-1 py-6 px-3 space-y-1 overflow-y-auto">
        {navItems.map((item) => (
          <NavItem
            key={item.href}
            href={item.href}
            icon={item.icon}
            label={item.label}
            active={location === item.href}
          />
        ))}
        
        <div className="mt-6 pt-6 border-t border-sidebar-border/30 px-3">
           <p className="text-xs font-semibold text-sidebar-foreground/40 uppercase mb-2 px-2">Apps</p>
           <NavItem href="/kiosk" icon={MonitorSmartphone} label="Kiosk Mode" active={location === "/kiosk"} />
        </div>
      </div>

      <div className="p-4 border-t border-sidebar-border/30">
        <NavItem href="/settings" icon={Settings} label="Settings" active={location === "/settings"} />
        <button className="w-full flex items-center gap-3 px-3 py-2.5 mt-1 rounded-md text-destructive hover:bg-destructive/10 transition-colors text-left">
          <LogOut className="h-5 w-5" />
          <span>Sign Out</span>
        </button>
      </div>
    </div>
  );

  return (
    <div className="min-h-screen bg-background flex">
      {/* Desktop Sidebar */}
      <aside className="hidden md:block w-64 bg-sidebar border-r border-border/50 fixed inset-y-0 left-0 z-50 shadow-xl">
        <SidebarContent />
      </aside>

      {/* Mobile Header */}
      <div className="md:hidden fixed top-0 left-0 right-0 h-16 bg-sidebar border-b border-border/50 flex items-center px-4 z-50 justify-between">
        <div className="flex items-center gap-2">
          <div className="h-8 w-8 rounded-lg bg-secondary flex items-center justify-center">
            <span className="font-serif font-bold text-secondary-foreground text-lg">J</span>
          </div>
          <span className="font-serif font-bold text-xl text-sidebar-foreground">JamHR</span>
        </div>
        <Sheet open={isMobileOpen} onOpenChange={setIsMobileOpen}>
          <SheetTrigger asChild>
            <Button variant="ghost" size="icon" className="text-sidebar-foreground">
              <Menu className="h-6 w-6" />
            </Button>
          </SheetTrigger>
          <SheetContent side="left" className="p-0 bg-sidebar border-r-sidebar-border w-72">
            <SidebarContent />
          </SheetContent>
        </Sheet>
      </div>

      {/* Main Content */}
      <main className="flex-1 md:ml-64 pt-16 md:pt-0 min-h-screen transition-all duration-300">
        <div className="max-w-7xl mx-auto p-4 md:p-8">
          {children}
        </div>
      </main>
    </div>
  );
}