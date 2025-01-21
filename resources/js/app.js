import Vue from "vue";
import VueRouter from "vue-router";
import { BootstrapVue, IconsPlugin } from "bootstrap-vue";
// import 'bootstrap/dist/css/bootstrap.css'
// import 'bootstrap-vue/dist/bootstrap-vue.css'
import { BButton } from "bootstrap-vue";

import axios from "axios";
import VueAxios from "vue-axios";
import VTooltip from "v-tooltip";

import VueSweetalert2 from "vue-sweetalert2";
import "sweetalert2/dist/sweetalert2.min.css";

// import VueConfirmDialog from "vue-confirm-dialog";
// import Dialog from 'bootstrap-vue-dialog'
import VueSimpleAlert from "vue-simple-alert";

import { ValidationProvider, ValidationObserver, extend } from "vee-validate";
import {
    required,
    // requiredfield,
    email,
    integer,
    digits,
    alpha_spaces,
    min_value,
    max
} from "vee-validate/dist/rules";
import JsonCSV from "vue-json-csv";
import ImageUploader from "vue-image-upload-resize";
import VueClipboard from "vue-clipboard2";
import vueCountryRegionSelect from "vue-country-region-select";
import { CalendarPlugin } from "bootstrap-vue";
import { BFormDatepicker } from "bootstrap-vue";

extend("required", {
    ...required,
    message: "This field is required"
});
// extend("requiredfield", {
//     ...requiredfield,
//     message: "{_field_} is required"
// });
extend("email", {
    ...email,
    message: "Invalid e-mail"
});
extend("integer", {
    ...integer,
    message: "This field must be a number"
});
extend("digits", {
    ...digits,
    message: "Number only and should contain 3 digits"
});
extend("alpha_spaces", {
    ...alpha_spaces,
    message: "May only contain alphabetic characters and space"
});
extend("min_value", {
    ...min_value,
    message: "Field must have at least 1 value and must be a number"
});
extend("max", {
    ...max,
    message: "Exceeded the max input"
});
extend("abs-integer", {
    validate(val) {
        return /^\d+$/.exec(val);
    },
    message: "This field must only contain number"
});
extend("decimal", {
    validate: (value, { decimals = "*", separator = "." } = {}) => {
        if (value === null || value === undefined || value === "") {
            return {
                valid: false
            };
        }
        if (Number(decimals) === 0) {
            return {
                valid: /^-?\d*$/.test(value)
            };
        }
        const regexPart = decimals === "*" ? "+" : `{1,${decimals}}`;
        const regex = new RegExp(
            `^[-+]?\\d*(\\${separator}\\d${regexPart})?([eE]{1}[-]?\\d+)?$`
        );

        return {
            valid: regex.test(value)
        };
    },
    message: "The {_field_} field must contain only decimal values"
});

// This rule checks if a prop within a json object has a minimum length of x
extend("json_min", {
    validate(value) {
        if (value.hh == "" || value.mm == "" || value.A == "") return false;
        return Object.keys(value).length === 3;
    },
    message: "This field is required"
});
Vue.component("ValidationProvider", ValidationProvider);
Vue.component("ValidationObserver", ValidationObserver);
Vue.component("downloadCsv", JsonCSV);
Vue.component("b-button", BButton);
Vue.use(ImageUploader);
VueClipboard.config.autoSetContainer = true; // add this line
Vue.use(VueClipboard);
Vue.use(vueCountryRegionSelect);
Vue.use(CalendarPlugin);
Vue.component("b-form-datepicker", BFormDatepicker);

import { library } from "@fortawesome/fontawesome-svg-core";
import {
    faExternalLinkSquareAlt,
    faPencilAlt,
    faSearch,
    faSave,
    faBookmark,
    faColumns,
    faPlus,
    faUserPlus,
    faArrowCircleRight,
    faTimes,
    faBars,
    faEyeSlash,
    faCheck,
    faExclamation,
    faEye,
    faPrint,
    faLock,
    faStar,
    faCalendarAlt,
    faUsers,
    faMoneyCheckAlt,
    faBuilding,
    faUserShield,
    faChartLine,
    faBriefcase,
    faDollarSign,
    faChevronRight,
    faChevronDown,
    faHome,
    faEllipsisH,
    faCaretLeft,
    faSlidersH,
    faCaretRight,
    faCoins,
    faSignal
} from "@fortawesome/free-solid-svg-icons";
import {
    faEnvelope,
    faListAlt,
    faCheckSquare,
    faSquare,
    faTrashAlt,
    faCalendar,
} from "@fortawesome/free-regular-svg-icons";
import {
    FontAwesomeIcon,
    FontAwesomeLayers
} from "@fortawesome/vue-fontawesome";
import { component } from "vuedraggable";
library.add(
    faListAlt,
    faExternalLinkSquareAlt,
    faPencilAlt,
    faSearch,
    faCoins,
    faCaretLeft,
    faCaretRight,
    faEllipsisH,
    faSlidersH,
    faSave,
    faBookmark,
    faColumns,
    faEnvelope,
    faCheckSquare,
    faSquare,
    faHome,
    faPlus,
    faCheck,
    faUserPlus,
    faCalendarAlt,
    faArrowCircleRight,
    faTimes,
    faBars,
    faTrashAlt,
    faEyeSlash,
    faEye,
    faExclamation,
    faPrint,
    faLock,
    faStar,
    faCalendar,
    faUsers,           // Added for 'Employee Management'
    faMoneyCheckAlt,   // Added for 'Payroll'
    faBuilding,        // Added for 'Branches'
    faUserShield,      // Added for 'Admin Users'
    faChartLine,       // Added for 'Reports & Analytics'
    faBriefcase,
    faDollarSign,
    faChevronDown,
    faChevronRight,
    faSignal
);
Vue.component("font-awesome-icon", FontAwesomeIcon);
Vue.component("font-awesome-layers", FontAwesomeLayers);

Vue.use(VueRouter);

Vue.use(BootstrapVue);

Vue.use(IconsPlugin);
Vue.use(VueAxios, axios);
Vue.use(VueSweetalert2);
Vue.use(require("vue-moment"));
Vue.use(VTooltip);
// Vue.use(VueConfirmDialog);
// Vue.use(Dialog)
Vue.use(VueSimpleAlert);

// Components
Vue.component(
    "header-component",
    require("./components/shared/Header").default
);
// Client header
Vue.component(
    "client-header-component",
    require("./components/shared/ClientHeader").default
);
// Vue.component("vue-confirm-dialog", VueConfirmDialog.default);

const routes = [
    {
        path: "/",
        component: require("./components/client/ClientLogin.vue").default,
        name: "client_login"
    },
    {
        path: "/schedules",
        component: require("./components/__Schedules.vue").default,
        name: "schedules",
        redirect: "/schedules",
        meta: {
            requiresAuth: true, 
            roles: ["HR"], 
        },
        children: [
            {
                path: "",
                component: require("./components/Schedules.vue").default,
                name: "schedules-index"
            },
            {
                path: "timerequestoff",
                component: require("./components/TimeRequestOff.vue").default,
                name: "timerequestoff"
            }
        ]
    },

    {
        path: "/reports/",
        component: require("./components/__MainReports.vue").default,
        name: "reports",
        redirect: "/reports/",
        meta: {
            requiresAuth: true, 
            roles: ["admin", "HR"], // Admin or HR roles can access this
        },
        children: [
            {
                path: "report-index",
                component: require("./components/Reports.vue").default,
                name: "report-index"
            },
            {
                path: "/",
                component: require("./components/OfficerReport.vue").default,
                name: "officer-report"
            },
            {
                path: "parking-ticket",
                component: require("./components/ParkingTicket.vue").default,
                name: "parking-ticket"
            },
            {
                path: "alert",
                component: require("./components/employees/Alert.vue").default,
                name: "report-alert"
            }
        ]
    },
    {
        path: "/employees/",
        component: require("./components/employees/__Main.vue").default,
        name: "employees",
        redirect: "/employees/",
        meta: {
            requiresAuth: true, 
            roles: ["HR"]
        },
        children: [
            {
                path: "/",
                component: require("./components/employees/Index.vue").default,
                name: "employee-index",           
            },
            {
                path: "positions",
                component: require("./components/employees/Positions.vue").default,
                name: "employee-positions"
            },
            {
                path: "notifications",
                component: require("./components/employees/Notifications.vue")
                    .default,
                name: "employee-notifications"
            },
            {
                path: "officer-logs",
                component: require("./components/employees/Officer-Logs.vue")
                    .default,
                name: "officer-logs"
            },
            {
                path: "deleted",
                component: require("./components/employees/Deleted.vue")
                    .default,
                name: "employee-deleted"
            },
            {
                path: "alert",
                component: require("./components/employees/Alert.vue").default,
                name: "employee-alert"
            }
        ]
    },
    {
        path: "/on-now/",
        component: require("./components/OnNow.vue").default,
        name: "on-now1",
        redirect: "/on-now/",
        meta: {},
        children: [
            {
                path: "/",
                component: require("./components/on-now/On_Now.vue").default,
                name: "on-now"
            },
            {
                path: "on-later",
                component: require("./components/on-now/On_Later.vue").default,
                name: "on-later"
            }
        ]
    },
    {
        path: "/messaging/",
        component: require("./components/__MainMessaging.vue").default,
        name: "messaging",
        redirect: "/messaging/",
        children: [
            {
                path: "/",
                component: require("./components/Messaging.vue").default,
                name: "mlist"
            },
            {
                path: "/chat/:id/:user_id",
                component: require("./components/MessagingChat.vue").default,
                name: "chat"
            }
        ]
    },
    {
        path: "/settings",
        component: require("./components/Settings.vue").default,
        name: "settings",
        meta: {}
    },
    {
        path: "/client/",
        component: require("./components/__Client.vue").default,
        name: "client",
        redirect: "/client/",
        meta: {},
        children: [
            {
                path: "clindex",
                component: require("./components/Client.vue").default,
                name: "clindex"
            },
            {
                path: "properties/:id",
                component: require("./components/Client-View.vue").default,
                name: "properties"
            },
            {
                path: "client-properties",
                component: require("./components/Client-Properties.vue")
                    .default,
                name: "client-properties"
            },
            {
                path: "clientadd",
                component: require("./components/Client-Add.vue").default,
                name: "clientadd"
            }
        ]
    },
    {
        path: "/admin/",
        component: require("./components/__Admin.vue").default,
        name: "admin",
        redirect: "/admin/",
        meta: {},
        children: [
            {
                path: "adminindex",
                component: require("./components/Admin.vue").default,
                name: "adminindex"
            },
            {
                path: "adminadd",
                component: require("./components/Admin-Add.vue").default,
                name: "adminadd"
            }
        ]
    },
    {
        path: "/client_login",
        component: require("./components/client/ClientLogin.vue").default,
        name: "client_login",
        meta: {}
    },
    {
        path: "/profile",
        component: require("./components/client/Profile.vue").default,
        name: "profile"
    },

    {
        path: "/login",
        component: require("./components/Login.vue").default,
        name: "login"
    },
    {
        path: "/client-reset",
        component: require("./components/client/ClientReset.vue").default,
        name: "client-reset",
        meta: {}
    },
    {
        path: "/cd/",
        component: require("./components/client/_Client.vue").default,
        //component: require('./components/employees/__Main.vue').default,
        name: "cd",
        redirect: "/cd/",
        meta: {},
        children: [
            {
                // path: '/',
                // component: require('./components/client/Properties.vue').default,
                // name: 'cproperties'
                path: "/",
                component: require("./components/employees/Index.vue").default,
                name: "employee-index"
            },
            {
                path: "invoice",
                component: require("./components/client/Invoice.vue").default,
                name: "cinvoice"
            },
            {
                path: "msg",
                component: require("./components/client/Messaging.vue").default,
                name: "cmsg"
            },
            {
                path: "/cmsgchat/:id/:user_id",
                component: require("./components/client/MessagingChat.vue")
                    .default,
                name: "cmsgchat"
            },
            {
                path: "creport/",
                component: require("./components/client/_MainReport.vue")
                    .default,
                name: "creport",
                redirect: "creport/",
                children: [
                    {
                        path: "/",
                        component: require("./components/client/HourlyReport.vue")
                            .default,
                        name: "HourlyReport"
                    },
                    {
                        path: "ticket",
                        component: require("./components/client/ParkingTicket.vue")
                            .default,
                        name: "ticket"
                    },
                    {
                        path: "incident",
                        component: require("./components/client/IncidentReport.vue")
                            .default,
                        name: "incident"
                    },
                    {
                        path: "maintain",
                        component: require("./components/client/MaintainReport.vue")
                            .default,
                        name: "maintain"
                    },
                    {
                        path: "sosreport",
                        component: require("./components/employees/Alert.vue")
                            .default,
                        name: "sosreport"
                    }
                ]
            }
        ]
    },

    // Printable
    {
        path: "/print-report",
        component: require("./components/__printable/Report.vue").default,
        name: "print-report",
        meta: {}
    },
    {
        path: "/print-schedule",
        component: require("./components/__printable/Schedule.vue").default,
        name: "print-schedule",
        meta: {}
    },
    {
        path: "/claims/",
        component: require("./components/Payrole/__Claims.vue").default,
        name: "Claims",
    },
	{
        path: "/Salary/",
        component: require("./components/Payrole/__payments.vue").default,
        name: "Salary",
        meta: {
            requiresAuth: false, 
            roles: ["admin"], 
          },
    },
	{
        path: "/attendance/",
        component: require("./components/EmployeeManagement/Attendance.vue").default,
        name: "attendance",
    },
	{
        path: "/leave/",
        component: require("./components/EmployeeManagement/Leave.vue").default,
        name: "leave",
    },
	{
        path: "/requests/",
        component: require("./components/EmployeeManagement/Requests.vue").default,
        name: "requests",
    },
	{
        path: "/payroll/",
        component: require("./components/ReportsAndAnalytics/__Payroll.vue").default,
        name: "payroll",
    },
	{
        path: "/performance/",
        component: require("./components/ReportsAndAnalytics/__Performance.vue").default,
        name: "performance",
    },
	{
        path: "/hranalytics/",
        component: require("./components/ReportsAndAnalytics/__Hranalytics.vue").default,
        name: "hranalytics",
    },
    {
        path: "/listing/",
        component: require("./components/JobsList/__Listing.vue").default,
        name: "Listing",
    },
    // {
    //     path: "/payrole/payments",
    //     component: require("./components/Payrole/__payments.vue").default,
    //     name: "payments",
    // },
    {
        path: "/payrole/tds",
        component: require("./components/Payrole/__Tax.vue").default,
        name: "Tax",
    },
    {
        path: "/create/",
        component: require("./components/JobsList/__CreateForm.vue").default,
        name: "Create",
    },
    {
        path: "/onoffboarding/",
        component: require("./components/EmployeeManagement/__Onoffboarding.vue").default,
        name: "onoffboarding",
    },
    {
        path: "/onoffboarding/",
        component: require("./components/EmployeeManagement/__Onoffboarding.vue").default,
        name: "onoffboarding",
    }
    ,
    {
        path: "/navigation/:job_number",
        component: require("./components/JobsList/__Navigation.vue").default,
        name: "navigation",
    },
    {
        path: "/apply",
        component: require("./components/JobsList/__Apply.vue").default,
        name: "apply",
    },
    {
        path: "/applications",
        component: require("./components/JobsList/__Candidates.vue").default,
        name: "applications",
    },
    {
        path: "/applicant",
        component: require("./components/JobsList/__Applicant_detail_list.vue").default,
        name: "applicant",
    },
    {
        path: "/paymentdetails",
        component: require("./components/Payrole/PaymentDetails.vue").default,
        name: "paymentdetails",
    },
    {
        path: "/runpayroll",
        component: require("./components/Payrole/RunPayroll.vue").default,
        name: "runpayroll",
    },
    {
        path: "/runpayrollscr",
        component: require("./components/Payrole/RunPayrollScr.vue").default,
        name: "runpayrollscr",
    },
    {
        path: "/tdscalculation",
        component: require("./components/Payrole/PayrollPage.vue").default,
        name: "tdscalculation",
    },
    {
        path: "/attendance-chart",
        component: require("./components/Chart/PieChart.vue").default,
        name: "attendance-chart",
    },
    {
        path: "/unauthorized",
        component: require("./components/Unauthorized.vue").default,
        name: "unauthorized",
    }
    
];

const router = new VueRouter({
    linkActiveClass: "active", // set as default value for active links
    mode: "history",
    routes,
    scrollBehavior(to, from, savedPosition) {
        return { x: 0, y: 0 };
    }
});
function getUserInfo() {
    return {
        isAuthenticated: !!localStorage.getItem("accesstoken"),
       role: localStorage.getItem("role") || "guest"
    };
}
router.beforeEach((to, from, next) => {
const { isAuthenticated, role } = getUserInfo();

    axios.interceptors.response.use(
        function(response) {
            return response;
        },
        function(error) {
            // if (error.response.status === 401) {
            // 	localStorage.removeItem('accesstoken')
            // 	localStorage.removeItem('admin')
            // 	localStorage.removeItem('user')
            // 	next('/')
            // }
            return Promise.reject(error);
        }
    );
    axios.defaults.headers.common["Accept"] = `application/json`;
    axios.defaults.headers.common["Authorization"] =
        `Bearer ` + JSON.parse(localStorage.getItem("accesstoken"));
    // var isAuthenticated = !!localStorage.getItem("user");
    // if (to.matched.some(record => record.meta.requiresAuth) && !isAuthenticated) {
    if (to.name == "profile" || to.name == "cmsg") {
        var arr = [
            "https://unpkg.com/bootstrap/dist/css/bootstrap.min.css",
            "https://unpkg.com/bootstrap-vue@latest/dist/bootstrap-vue.min.css"
        ];
        arr.forEach(route => {
            const link_el = document.createElement("link");
            link_el.setAttribute("href", route);
            link_el.setAttribute("type", "stylesheet");
            link_el.setAttribute("id", "bootstrapcss");

            document.head.appendChild(link_el);
        });        
        next();
    } else {
        next();
    }
    if (to.matched.some(record => record.meta.requiresAuth)) {
        if (!isAuthenticated) {
            return next({ path: '/login' }); 
        }        
    }
    // if (to.meta.requiresAuth) {
    //     const hasPermission = to.meta.roles.includes(role);
          
    //       console.log("Has Permission:", hasPermission);

    
    //     if (hasPermission) {
    //       next();
    //     } else {
    //       next("/unauthorized");
    //     }
    // }
     next();
});
const app = new Vue({
    el: "#app",
    router
});
