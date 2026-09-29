import  { HOME_URL } from '../reducers/Url'
export const getHomeData = (data) => {
    // console.log("hello");
    return async dispatch => {
        await fetch(`${HOME_URL}`, {
            method: 'POST', //Request Type
            headers: {
                //Header Defination
                'Content-Type': 'application/json',
            },
            // body: JSON.stringify(data), //post body
        }).then(async (res) => {
            let response = await res.json();
            // if (response.status) {
            dispatch(HomeData(response))
            // } else {

            // }
        }
        ).catch(err => {
            console.log("getHomeData", err)
        })
    }
};