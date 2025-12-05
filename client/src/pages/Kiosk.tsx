import { useState, useEffect } from "react";
import { Card, CardContent } from "@/components/ui/card";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Badge } from "@/components/ui/badge";
import { Clock, User, LogIn, LogOut, Coffee, AlertTriangle } from "lucide-react";
import { cn } from "@/lib/utils";

export default function Kiosk() {
  const [time, setTime] = useState(new Date());
  const [pin, setPin] = useState("");
  const [mode, setMode] = useState<"idle" | "entry">("idle");
  const [status, setStatus] = useState<"success" | "error" | null>(null);
  const [message, setMessage] = useState("");

  useEffect(() => {
    const timer = setInterval(() => setTime(new Date()), 1000);
    return () => clearInterval(timer);
  }, []);

  const handlePinClick = (num: string) => {
    if (pin.length < 4) setPin(prev => prev + num);
  };

  const handleClear = () => setPin("");
  
  const handleSubmit = (action: string) => {
    if (pin.length === 4) {
      // Mock success
      setStatus("success");
      setMessage(`${action} Successful for Marcus Brown at ${time.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})}`);
      setPin("");
      setTimeout(() => {
        setStatus(null);
        setMode("idle");
      }, 3000);
    } else {
      setStatus("error");
      setMessage("Invalid PIN. Please try again.");
      setTimeout(() => setStatus(null), 2000);
    }
  };

  return (
    <div className="min-h-screen bg-sidebar flex items-center justify-center p-4">
      <Card className="w-full max-w-4xl h-[600px] border-none shadow-2xl overflow-hidden bg-background grid grid-cols-1 md:grid-cols-2">
        
        {/* Left Panel - Branding & Time */}
        <div className="bg-primary p-8 flex flex-col justify-between text-primary-foreground relative overflow-hidden">
          <div className="absolute top-0 left-0 w-full h-full opacity-10 pointer-events-none">
             {/* Abstract Pattern */}
             <svg width="100%" height="100%" viewBox="0 0 100 100" preserveAspectRatio="none">
               <path d="M0 0 L100 0 L100 100 Z" fill="white" />
             </svg>
          </div>
          
          <div className="relative z-10">
            <div className="flex items-center gap-3 mb-2">
              <div className="h-10 w-10 rounded-lg bg-secondary flex items-center justify-center shadow-lg">
                <span className="font-serif font-bold text-secondary-foreground text-xl">J</span>
              </div>
              <span className="font-serif font-bold text-2xl tracking-tight">JamHR Kiosk</span>
            </div>
            <Badge variant="outline" className="text-primary-foreground border-primary-foreground/30 bg-primary-foreground/10">
              Kingston HQ - Front Desk
            </Badge>
          </div>

          <div className="relative z-10 space-y-2">
            <div className="text-6xl font-bold tracking-tighter font-mono">
              {time.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })}
            </div>
            <div className="text-xl font-medium opacity-80">
              {time.toLocaleDateString(undefined, { weekday: 'long', month: 'long', day: 'numeric' })}
            </div>
          </div>

          <div className="relative z-10 text-sm opacity-60">
            System Online • v2.4.1
          </div>
        </div>

        {/* Right Panel - Interaction */}
        <div className="p-8 flex flex-col justify-center bg-white">
          
          {status === "success" ? (
            <div className="flex flex-col items-center text-center animate-in zoom-in duration-300">
              <div className="h-24 w-24 bg-emerald-100 rounded-full flex items-center justify-center mb-6">
                <CheckCircle2 className="h-12 w-12 text-emerald-600" />
              </div>
              <h2 className="text-2xl font-bold text-emerald-700 mb-2">Success!</h2>
              <p className="text-muted-foreground text-lg">{message}</p>
            </div>
          ) : status === "error" ? (
             <div className="flex flex-col items-center text-center animate-in shake duration-300">
              <div className="h-24 w-24 bg-red-100 rounded-full flex items-center justify-center mb-6">
                <AlertTriangle className="h-12 w-12 text-red-600" />
              </div>
              <h2 className="text-2xl font-bold text-red-700 mb-2">Error</h2>
              <p className="text-muted-foreground text-lg">{message}</p>
            </div>
          ) : mode === "idle" ? (
            <div className="space-y-6 animate-in fade-in slide-in-from-right-8 duration-500">
              <div className="text-center mb-8">
                <h2 className="text-2xl font-serif font-bold text-foreground">Welcome</h2>
                <p className="text-muted-foreground">Select an action to continue</p>
              </div>
              
              <div className="grid grid-cols-2 gap-4">
                <Button 
                  className="h-32 flex flex-col items-center justify-center gap-3 bg-emerald-600 hover:bg-emerald-700 text-white shadow-lg transition-all hover:scale-[1.02]"
                  onClick={() => setMode("entry")}
                >
                  <LogIn className="h-8 w-8" />
                  <span className="text-lg font-medium">Clock In</span>
                </Button>
                
                <Button 
                  className="h-32 flex flex-col items-center justify-center gap-3 bg-slate-600 hover:bg-slate-700 text-white shadow-lg transition-all hover:scale-[1.02]"
                   onClick={() => setMode("entry")}
                >
                  <LogOut className="h-8 w-8" />
                  <span className="text-lg font-medium">Clock Out</span>
                </Button>

                <Button variant="outline" className="h-24 flex flex-col items-center justify-center gap-2 border-2 hover:bg-slate-50" onClick={() => setMode("entry")}>
                  <Coffee className="h-6 w-6 text-secondary-foreground" />
                  <span>Start Break</span>
                </Button>

                <Button variant="outline" className="h-24 flex flex-col items-center justify-center gap-2 border-2 hover:bg-slate-50" onClick={() => setMode("entry")}>
                  <CheckCircle2 className="h-6 w-6 text-secondary-foreground" />
                  <span>End Break</span>
                </Button>
              </div>
            </div>
          ) : (
            <div className="space-y-6 animate-in fade-in slide-in-from-right-8 duration-500">
              <div className="text-center">
                <h2 className="text-xl font-bold text-foreground">Enter Employee PIN</h2>
                <div className="flex justify-center gap-2 mt-4">
                  {[0, 1, 2, 3].map((i) => (
                    <div key={i} className={cn(
                      "w-4 h-4 rounded-full transition-colors duration-200",
                      pin.length > i ? "bg-primary" : "bg-muted-foreground/20"
                    )} />
                  ))}
                </div>
              </div>

              <div className="grid grid-cols-3 gap-3 max-w-[280px] mx-auto">
                {[1, 2, 3, 4, 5, 6, 7, 8, 9].map((num) => (
                  <Button 
                    key={num} 
                    variant="outline" 
                    className="h-16 text-xl font-bold border-2 hover:border-primary hover:bg-primary/5"
                    onClick={() => handlePinClick(num.toString())}
                  >
                    {num}
                  </Button>
                ))}
                <Button variant="ghost" className="h-16 text-sm font-medium text-muted-foreground" onClick={() => setMode("idle")}>Cancel</Button>
                <Button 
                  variant="outline" 
                  className="h-16 text-xl font-bold border-2 hover:border-primary hover:bg-primary/5"
                  onClick={() => handlePinClick("0")}
                >
                  0
                </Button>
                 <Button variant="ghost" className="h-16 text-sm font-medium text-destructive hover:text-destructive" onClick={handleClear}>Clear</Button>
              </div>
              
              <Button className="w-full max-w-[280px] mx-auto block h-12 text-lg" onClick={() => handleSubmit("Clock In")}>
                Confirm
              </Button>
            </div>
          )}
        </div>
      </Card>
    </div>
  );
}

// Helper component for success/error icons
function CheckCircle2(props: any) {
  return (
    <svg
      {...props}
      xmlns="http://www.w3.org/2000/svg"
      width="24"
      height="24"
      viewBox="0 0 24 24"
      fill="none"
      stroke="currentColor"
      strokeWidth="2"
      strokeLinecap="round"
      strokeLinejoin="round"
    >
      <circle cx="12" cy="12" r="10" />
      <path d="m9 12 2 2 4-4" />
    </svg>
  )
}
