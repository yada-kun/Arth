import axios from "axios";

export const axiosClient = new axios.create({
    baseURL: "http://localhost",
    withCredentials: true,
    withXSRFToken: true,
});

let retryCount = 0;

async function refreshAccessToken() {
    try {
        const response = await axiosClient.post("auth/refresh");
        const newAccessToken = response.data.data.access_token;
        localStorage.removeItem("accessToken");
        localStorage.setItem("accessToken", newAccessToken);
        return newAccessToken;
    } catch (error) {
        // Handle refresh token error
        localStorage.removeItem("accessToken");
        localStorage.removeItem("user");
        window.location.href = "/";
        throw error;
    }
}

axios.interceptors.request.use(
    function (config) {
        // Do something before request is sent
        return config;
    },
    function (error) {
        // Do something with request error
        return Promise.reject(error);
    }
);

// Add a response interceptor
axios.interceptors.response.use(
    function (response) {
        // Any status code that lie within the range of 2xx cause this function to trigger
        // Do something with response data
        return response;
    },
    async function (error) {
        if (retryCount < 3 && error.statusCode === 401) {
            retryCount++;
            await refreshAccessToken();

            // Retry the original request with the new access token
            return axiosClient(originalRequest);
        } else {
            return Promise.reject(error);
        }
        // Any status codes that falls outside the range of 2xx cause this function to trigger
        // Do something with response error
    }
);
