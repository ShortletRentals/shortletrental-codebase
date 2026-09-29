import axios from "axios"
import AsyncStorage from '@react-native-async-storage/async-storage';
import { CREATE_ACCESS_TOKEN } from "./Webconstant";


var TOKEN = null

const storeAccessToken = async (value) => {
    try {
        const jsonValue = JSON.stringify(value)
        await AsyncStorage.setItem('ACCESS_TOKEN', jsonValue)
        console.log('async set');
    } catch {
        console.log('error');
    }
}

const getAccessToken = async () => {
    try {
        const jsonValue = await AsyncStorage.getItem('ACCESS_TOKEN')

        if (jsonValue != null) {
            let data = JSON.parse(jsonValue)
            TOKEN = data?.data?.access_token
            // console.log('async called for accesss', data?.data?.access_token)

        } else {
            let DATA = new FormData()
            DATA.append('grant_type', 'client_credentials')
            DATA.append('client_id', 'c82769bc-1fa5-47a8-b441-c541bd2241d2@33ec418b-9776-46f1-9cff-c19495bd02f2')
            DATA.append('client_secret', 'AF7B8b3M3Ry6P5nJwio21DB4gyc/YNoenAGeN/MqpWU=')
            DATA.append('resource', '00000003-0000-0ff1-ce00-000000000000/barwarealestate.sharepoint.com@33ec418b-9776-46f1-9cff-c19495bd02f2')

            try {
                const response = await axios({
                    url: CREATE_ACCESS_TOKEN,
                    method: 'post',
                    data: DATA,
                });

                if (response?.status == 401) {
                    throw new Error("An error has occurred");

                } else {
                    storeAccessToken(response)
                    // console.log('api called for accesss', response?.data?.access_token);
                    TOKEN = response?.data?.access_token
                }

            } catch (error) {
                console.log(error);
            }
        }
    } catch (e) {

    }
}

// const getAccessTokenFromApi = async () => {
//     let DATA = new FormData()
//     DATA.append('grant_type', 'client_credentials')
//     DATA.append('client_id', 'c82769bc-1fa5-47a8-b441-c541bd2241d2@33ec418b-9776-46f1-9cff-c19495bd02f2')
//     DATA.append('client_secret', 'AF7B8b3M3Ry6P5nJwio21DB4gyc/YNoenAGeN/MqpWU=')
//     DATA.append('resource', '00000003-0000-0ff1-ce00-000000000000/barwarealestate.sharepoint.com@33ec418b-9776-46f1-9cff-c19495bd02f2')

//     try {
//         const response = await axios({
//             url: CREATE_ACCESS_TOKEN,
//             method: 'post',
//             data: DATA,
//             // headers: 'header'
//         });
//         if (response?.status == 401) {
//             throw new Error("An error has occurred");
//         } else {
//             return response
//         }

//     } catch (error) {
//         console.log(error);
//     }
// }

export const Axios_Post = async (URL, body) => {
    return getAccessToken().then(() => {
        try {
            const response = axios({
                url: URL,
                method: 'post',
                data: body,
                headers: { 'Authorization': `Bearer ${TOKEN}`, 'Accept': 'application/json;odata=verbose', 'Content-Type': 'application/json;odata=verbose' }
            });

            if (response?.status == 401) {
                throw new Error("An error has occurred");
            } else {
                return response
                // return response
            }
        } catch (error) {
            console.log(error);
        }
    })

}

export const Axios_get = async (URL) => {
    console.log(URL);
    return getAccessToken().then(() => {
        try {
            const response = axios({
                url: URL,
                method: 'get',
                // data: body,
                headers: { 'Authorization': `Bearer ${TOKEN}`, 'Accept': 'application/json;odata=verbose', 'Content-Type': 'application/json;odata=verbose' }
            });
            console.log('ani cho', URL, TOKEN);
            if (response?.status == 401) {
                throw new Error("An error has occurred");
            } else {
                return response
                // return response
            }
        } catch (error) {
            console.log('error token',error);
        }
    })

}

export var httpRequest = {
    PostAPI: async (url)=>{
        return await axios({
            url: url,
            method: 'get',
            // data: body,
            headers: { 'Authorization': `Bearer ${'eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsIng1dCI6IjJaUXBKM1VwYmpBWVhZR2FYRUpsOGxWMFRPSSIsImtpZCI6IjJaUXBKM1VwYmpBWVhZR2FYRUpsOGxWMFRPSSJ9.eyJhdWQiOiIwMDAwMDAwMy0wMDAwLTBmZjEtY2UwMC0wMDAwMDAwMDAwMDAvYmFyd2FyZWFsZXN0YXRlLnNoYXJlcG9pbnQuY29tQDMzZWM0MThiLTk3NzYtNDZmMS05Y2ZmLWMxOTQ5NWJkMDJmMiIsImlzcyI6IjAwMDAwMDAxLTAwMDAtMDAwMC1jMDAwLTAwMDAwMDAwMDAwMEAzM2VjNDE4Yi05Nzc2LTQ2ZjEtOWNmZi1jMTk0OTViZDAyZjIiLCJpYXQiOjE2NTkzMjUxNjEsIm5iZiI6MTY1OTMyNTE2MSwiZXhwIjoxNjU5NDExODYxLCJpZGVudGl0eXByb3ZpZGVyIjoiMDAwMDAwMDEtMDAwMC0wMDAwLWMwMDAtMDAwMDAwMDAwMDAwQDMzZWM0MThiLTk3NzYtNDZmMS05Y2ZmLWMxOTQ5NWJkMDJmMiIsIm5hbWVpZCI6ImM4Mjc2OWJjLTFmYTUtNDdhOC1iNDQxLWM1NDFiZDIyNDFkMkAzM2VjNDE4Yi05Nzc2LTQ2ZjEtOWNmZi1jMTk0OTViZDAyZjIiLCJvaWQiOiJmNTJmZmU0OS1kN2JkLTRhZTYtODE1NS0wMjBhZWMyODViYzEiLCJzdWIiOiJmNTJmZmU0OS1kN2JkLTRhZTYtODE1NS0wMjBhZWMyODViYzEiLCJ0cnVzdGVkZm9yZGVsZWdhdGlvbiI6ImZhbHNlIn0.s6Qm1pWff_HNr9XsGo2sv2BAZUVFjK_522-bMnyxO9GVY8DU6tX8WnNgq2fty6qg46xiRpKQAegTtHXEnTaHVi_aiDuOVvnQI2hLNNW5y00L27ktWD2shDXeO7E0G0h_eeB7dlpblAAwkGBWFr57qDxEVc1fD78dBR3BogftGQFAM96ZDfY7WJc-d8DwpR3U_GSUgDBVs0FAjzDbrcvBAc8MDK34c8QTdTvzasO1vHWOFdDEST5w0yOpsulomosHP1pH-3wE1ZnlBWTdc7SrUthtL0RIVJMiVf7_rTcH0SFWnGSxlGrGUm1t2NdcVh973Ze_LIsVHJLxqTrK8kOlqQ'}`, 'Accept': 'application/json;odata=verbose', 'Content-Type': 'application/json;odata=verbose' }
        }).then(res=>{
            return res;
        }).catch(e=>{
            return e;
        })
    }
}
