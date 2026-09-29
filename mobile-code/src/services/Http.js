/* Enable code for API calling with GET & POST methods. */

// import axios from 'axios';

// const CallToResponse = {
//     GetResponse: function (requestUrl, token) {
//         return axios({
//             method: 'get',
//             url: requestUrl,
//             responseType: 'text',
//             headers: header,
//             timeout: 15000,
//         })
//             .then(function (response) {
//                 if (response.status == 200) {
//                     return JSON.stringify(response.data);
//                 } else {
//                     return null;
//                 }
//             })
//             .catch(error => {
//                 return null;
//             });
//     },

//     PostResponse: function (requestUrl, body = null, token) {
//         var header = {
//             // 'Content-Type': 'application/json',
//             Authorization: 'Bearer ' + token,
//             //   'Accept-Language': I18nManager.isRTL ? 'ar' : 'en',
//             Accept: 'application/json',
//         };

//         var data = new FormData();
//         if (body != null) {
//             for (const property in body) {
//                 data.append(property.toString(), body[property]);
//             }
//         }

//         return axios({
//             method: 'post',
//             url: base_url + requestUrl,
//             responseType: 'text',
//             headers: header,
//             data: body != null ? data : null,
//             timeout: 15000,
//         })
//             .then(function (response) {
//                 if (response.status == 200) {
//                     return response.data;
//                 } else {
//                     return null;
//                 }
//             })
//             .catch(error => {
//                 return null;
//             });
//     },
// };

// export default CallToResponse;
