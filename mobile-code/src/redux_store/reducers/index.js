import {HOME} from './types'
const initialState = {
    data:[],
    homeData:[]
    // loginData: {},
    // // otpData: {},
    // // signUpData: {},
    // // sendotpData: {},
    // // userProfile: {},
    // // errorvisible: false,
    // // enterDetailsManual: {},
    // // updateBatchData: {},
    // // addbottleForCarate: {},
    // // crateDetailsData: {},
    // // innerDetailsOfCarate: {},
    // // getCrateDetail: {},
    // // addInInventory: {},
    // // getInventoryDetails: {},

};

const Reducer = (state = initialState, action) => {
    switch (action.type) {
        case HOME:
            return { ...state, data: action.payload , homeData:action.payload };
        // case OTPDATA:
        //     return { ...state, otpData: action.payload };
        // case USERSIGNUP:
        //     return { ...state, signUpData: action.payload };
        // case SENDOTP:
        //     return { ...state, sendotpData: action.payload };
        // case USERPROFILE:
        //     return { ...state, userProfile: action.payload };
        // case ERRORVISIBLE:
        //     return { ...state, errorvisible: action.payload };
        // case MANUALENTRYDATA:
        //     return { ...state, enterDetailsManual: action.payload };
        // case UPDATEBATCH:
        //     return { ...state, updateBatchData: action.payload };
        // case ADDBOTTLE:
        //     return { ...state, addbottleForCarate: action.payload };
        // case BOTTLEDETAILS:
        //     return { ...state, crateDetails: action.payload };
        // case INNERDETAILS:
        //     return { ...state, innerDetailsOfCarate: action.payload };
        // case CRATEDATA:
        //     return { ...state, getCrateDetail: action.payload };
        // case ADDININVENTORY:
        //     return { ...state, addInInventory: action.payload };
        // case GETININVENTORYDETAIL:
        //     return { ...state, getInventoryDetails: action.payload };

        default:
            return state;
    }
}
export default Reducer;