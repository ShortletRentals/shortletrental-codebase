import Toast from "react-native-toast-message/lib/src/Toast"

export const customToast = (TYPE, MESSAGE) => {
    return (
        Toast.show({
            type: TYPE,
            text1: 'SHORTLET RENTAL SAYS',
            text2: MESSAGE
        })
    )

}