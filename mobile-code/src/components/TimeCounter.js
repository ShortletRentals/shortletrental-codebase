import React, { useState, useEffect } from "react";
import { View, Text, TouchableOpacity } from "react-native";
import { color } from "../styles/colors";

const TimeCounter = ({ startCounting, resetOtpState }) => {
    const [counter, setCounter] = useState(60);
    const [isResendEnable, setResendEnable] = useState(true);

    useEffect(() => {
        if (startCounting) {
            const timer =
                counter > 0
                    ? setInterval(() => {
                        setCounter(counter - 1);
                    }, 1000)
                    : setResendEnable(false);
            return () => clearInterval(timer);
        }
    }, [counter, startCounting]);

    const onResend = () => {
        resetOtpState();
        setResendEnable(true);
        if (counter > 0) {
        } else {
            setCounter(60);
        }
    };

    return (
        <View>
            <View style={{ marginVertical: 20, alignItems: "center" }}>
                {/* <TouchableOpacity disabled={isResendEnable} onPress={onResend}>
                    <Text
                        style={{
                            // fontFamily: font.font_SemiBold,
                            color: isResendEnable ? color.lightGray : color.white,
                            fontSize: 20,
                            textDecorationLine: "underline",
                        }}
                    >
                        Resend Code?
                    </Text>
                </TouchableOpacity> */}
            </View>

            <View style={{ alignItems: "center" }}>
                <Text
                    style={{
                        // fontFamily: font.font_SemiBold,
                        color: color.white,
                        fontSize: 20,
                    }}
                >
                    00:{counter?.toString()?.length < 2 ? `0${counter}` : counter}
                </Text>
            </View>

            {/* <Text style={styles.textTime}>{counter} seconds</Text>
      <View style={styles.resendContiner}>
        <Text style={styles.textReceive}>Didn’t receive code? </Text>
        <TouchableOpacity onPress={onResend}>
          <Text style={styles.textResend}>Resend Code</Text>
        </TouchableOpacity>
      </View> */}
        </View>
    );
};

export default TimeCounter;
