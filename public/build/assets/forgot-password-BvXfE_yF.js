import{m as n,j as e,L as d}from"./app-BkLTjr6G.js";import{L as c,I as p,a as x}from"./label-BdO6MuVc.js";import{T as u}from"./text-link-Cgk1m5iF.js";import{B as j}from"./app-logo-icon-BMZ6idhb.js";import{A as f,L as h}from"./auth-layout-BkKo3jtO.js";/* empty css            */import"./index-CxyJ5Ipo.js";function E({status:t}){const{data:r,setData:i,post:o,processing:a,errors:m}=n({email:""}),l=s=>{s.preventDefault(),o(route("password.email"))};return e.jsxs(f,{title:"Forgot password",description:"Enter your email to receive a password reset link",children:[e.jsx(d,{title:"Forgot password"}),t&&e.jsx("div",{className:"mb-4 text-center text-sm font-medium text-green-600",children:t}),e.jsxs("div",{className:"space-y-6",children:[e.jsxs("form",{onSubmit:l,children:[e.jsxs("div",{className:"grid gap-2",children:[e.jsx(c,{htmlFor:"email",children:"Email address"}),e.jsx(p,{id:"email",type:"email",name:"email",autoComplete:"off",value:r.email,autoFocus:!0,onChange:s=>i("email",s.target.value),placeholder:"email@example.com"}),e.jsx(x,{message:m.email})]}),e.jsx("div",{className:"my-6 flex items-center justify-start",children:e.jsxs(j,{className:"w-full",disabled:a,children:[a&&e.jsx(h,{className:"h-4 w-4 animate-spin"}),"Email password reset link"]})})]}),e.jsxs("div",{className:"text-muted-foreground space-x-1 text-center text-sm",children:[e.jsx("span",{children:"Or, return to"}),e.jsx(u,{href:route("login"),children:"log in"})]})]})]})}export{E as default};
